<?php

namespace App\Livewire\Student\Chat;

use App\Models\Classroom;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithFileUploads, WithPagination;

    public $selectedUser = null;

    public $selectedClass = null;

    public $messageText = '';

    public $attachment = null;

    public $searchTerm = '';

    public $messageType = 'user'; // 'user', 'class'

    public $unreadCount = 0;

    public $activeTab = 'classes'; // 'classes', 'users'

    public $isDragging = false;

    protected $listeners = [
        'messageReceived' => 'refreshMessages',
        'fileDropped' => 'handleFileDrop',
    ];

    public function mount()
    {
        $this->unreadCount = Message::unread(Auth::id())->count();
    }

    public function selectUser($userId)
    {
        $this->selectedUser = User::find($userId);
        $this->selectedClass = null;
        $this->messageType = 'user';
        $this->activeTab = 'users';
        $this->resetPage();
    }

    public function selectClass($classId)
    {
        $this->selectedClass = Classroom::with('users')->find($classId);
        $this->selectedUser = null;
        $this->messageType = 'class';
        $this->activeTab = 'classes';
        $this->resetPage();

        // Đánh dấu đã đọc
        $lastMsg = Message::where('class_id', $classId)->latest('id')->first();
        if ($lastMsg) {
            \App\Models\ClassroomMessageRead::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'class_id' => $classId,
                ],
                [
                    'last_read_message_id' => $lastMsg->id,
                    'last_read_at' => now(),
                ]
            );
        }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function sendMessage()
    {
        // Kiểm tra file trước khi validate
        if ($this->attachment) {
            try {
                // Kiểm tra file có hợp lệ không
                if (! $this->attachment->isValid()) {
                    $this->addError('attachment', 'File không hợp lệ hoặc bị hỏng.');

                    return;
                }

                // Kiểm tra kích thước
                if ($this->attachment->getSize() > 102400 * 1024) { // 100MB
                    $this->addError('attachment', 'File quá lớn. Kích thước tối đa là 100MB.');

                    return;
                }

                // Kiểm tra MIME type
                $allowedMimes = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'rar', '7z', 'mp3', 'm4a', 'wav', 'ogg', 'oga', 'flac', 'amr', 'webm', 'mp4'];
                $fileExtension = strtolower($this->attachment->getClientOriginalExtension());
                if (! in_array($fileExtension, $allowedMimes)) {
                    $this->addError('attachment', 'Định dạng file không được hỗ trợ.');

                    return;
                }
            } catch (\Exception $e) {
                $this->addError('attachment', 'Không thể xử lý file: '.$e->getMessage());

                return;
            }
        }

        $this->validate([
            'messageText' => 'nullable|string|max:1000',
        ]);

        if ((trim($this->messageText) === '' || $this->messageText === null) && ! $this->attachment) {
            $this->addError('messageText', 'Vui lòng nhập nội dung hoặc chọn tệp đính kèm.');

            return;
        }

        $messageData = [
            'sender_id' => Auth::id(),
            'message' => $this->messageText,
        ];

        if ($this->messageType === 'user' && $this->selectedUser) {
            $messageData['receiver_id'] = $this->selectedUser->id;
        } elseif ($this->messageType === 'class' && $this->selectedClass) {
            $messageData['class_id'] = $this->selectedClass->id;
        }

        if ($this->attachment) {
            try {
                Log::info('Uploading attachment', [
                    'original_name' => $this->attachment->getClientOriginalName(),
                    'size' => $this->attachment->getSize(),
                    'mime_type' => $this->attachment->getMimeType(),
                ]);

                $path = $this->attachment->store('chat-attachments', 'public');
                $messageData['attachment'] = $path;

                Log::info('Attachment uploaded successfully', ['path' => $path]);
            } catch (\Exception $e) {
                Log::error('Failed to upload attachment', [
                    'error' => $e->getMessage(),
                    'file' => $this->attachment->getClientOriginalName(),
                ]);
                $this->addError('attachment', 'Không thể tải lên tệp: '.$e->getMessage());

                return;
            }
        }

        $message = Message::create($messageData);

        // Dispatch event để broadcast tin nhắn
        Log::info('Dispatching MessageSent event', ['message_id' => $message->id]);
        \App\Events\MessageSent::dispatch($message);

        $this->messageText = '';
        $this->attachment = null;
        $this->dispatch('messageSent');
    }

    public function handleFileDrop($fileData)
    {
        // Xử lý file được kéo thả
        if (isset($fileData['file'])) {
            $this->attachment = $fileData['file'];
        }
    }

    public function getMessagesProperty()
    {
        if ($this->selectedUser) {
            return Message::where(function ($query) {
                $query->where('sender_id', Auth::id())
                    ->where('receiver_id', $this->selectedUser->id)
                    ->orWhere('sender_id', $this->selectedUser->id)
                    ->where('receiver_id', Auth::id());
            })->with(['sender', 'receiver'])->orderBy('created_at', 'desc')->paginate(20);
        }

        if ($this->selectedClass) {
            return Message::where('class_id', $this->selectedClass->id)
                ->with(['sender', 'receiver', 'classroom'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        return collect();
    }

    public function getUsersProperty()
    {
        $query = User::where('id', '!=', Auth::id())
            ->whereIn('role', ['admin', 'teacher']);

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->searchTerm.'%')
                    ->orWhere('email', 'like', '%'.$this->searchTerm.'%');
            });
        }

        return $query->orderBy('name')->get();
    }

    public function getClassesProperty()
    {
        $query = Classroom::whereHas('users', function ($q) {
            $q->where('users.id', Auth::id());
        });

        if ($this->searchTerm) {
            $query->where('name', 'like', '%'.$this->searchTerm.'%');
        }

        $classes = $query->orderBy('name')->get();

        foreach ($classes as $class) {
            $class->unread_messages_count = $class->unreadMessagesCountForUser(Auth::id());
        }

        return $classes;
    }

    public function refreshMessages()
    {
        $this->unreadCount = Message::unread(Auth::id())->count();
    }

    public function handleNewMessage($event)
    {
        $message = $event['message'] ?? null;
        if ($message) {
            $isRelevant = false;

            if ($this->selectedUser && $message['receiver_id'] == $this->selectedUser->id && $message['sender_id'] == Auth::id()) {
                $isRelevant = true;
            } elseif ($this->selectedUser && $message['sender_id'] == $this->selectedUser->id && $message['receiver_id'] == Auth::id()) {
                $isRelevant = true;
            } elseif ($this->selectedClass && $message['class_id'] == $this->selectedClass->id) {
                $isRelevant = true;
            }

            if ($isRelevant) {
                $this->refreshMessages();
                $this->dispatch('messageReceived');
            }
        }
    }

    public function downloadAttachment($messageId)
    {
        $message = Message::find($messageId);
        if ($message && $message->attachment) {
            $path = Storage::disk('public')->path($message->attachment);
            if (file_exists($path)) {
                return response()->download($path);
            }
        }

        return back()->with('error', 'File không tồn tại');
    }

    public function render()
    {
        return view('student.chat.index', [
            'messages' => $this->messages,
            'users' => $this->users,
            'classes' => $this->classes,
        ]);
    }
}
