<?php

namespace App\Livewire\Admin\Chat;

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

    public $messageType = 'user'; // 'user' or 'class'

    public $unreadCount = 0;

    public $memberSearch = '';

    public $addMemberSearch = '';

    public $allUsers;

    public $activeTab = 'users'; // 'users' hoặc 'classes'

    protected $listeners = [
        'messageReceived' => 'refreshMessages',
    ];

    public function mount()
    {
        $this->unreadCount = Message::unread(Auth::id())->count();
        $this->allUsers = User::all();
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
        Log::info('[Chat Debug] selectClass: Chọn lớp', [
            'class_id' => $classId,
            'selectedClass' => $this->selectedClass ? $this->selectedClass->toArray() : null,
            'current_user_id' => Auth::id(),
        ]);
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

    public function testUpload()
    {
        if ($this->attachment) {
            try {
                Log::info('Test upload - File info:', [
                    'name' => $this->attachment->getClientOriginalName(),
                    'size' => $this->attachment->getSize(),
                    'mime' => $this->attachment->getMimeType(),
                    'isValid' => $this->attachment->isValid(),
                    'error' => $this->attachment->getError(),
                ]);

                $path = $this->attachment->store('chat-attachments', 'public');
                $this->dispatch('alert', ['type' => 'success', 'message' => 'File uploaded: '.$path]);
            } catch (\Exception $e) {
                Log::error('Test upload failed:', ['error' => $e->getMessage()]);
                $this->dispatch('alert', ['type' => 'error', 'message' => 'Upload failed: '.$e->getMessage()]);
            }
        } else {
            $this->dispatch('alert', ['type' => 'warning', 'message' => 'No file selected']);
        }
    }

    public function updatedAttachment()
    {
        if ($this->attachment) {
            Log::info('Attachment updated:', [
                'name' => $this->attachment->getClientOriginalName(),
                'size' => $this->attachment->getSize(),
                'mime' => $this->attachment->getMimeType(),
                'isValid' => $this->attachment->isValid(),
                'error' => $this->attachment->getError(),
            ]);
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
        $query = User::where('id', '!=', Auth::id());

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
        // Nếu là admin, hiển thị tất cả lớp học
        if (Auth::user()->role === 'admin') {
            $query = Classroom::query();
        } else {
            // Nếu không phải admin, chỉ hiển thị lớp mà user được gán
            $query = Classroom::whereHas('users', function ($q) {
                $q->where('users.id', Auth::id());
            });
        }

        if ($this->searchTerm) {
            $query->where('name', 'like', '%'.$this->searchTerm.'%');
        }

        $classes = $query->orderBy('name')->get();

        foreach ($classes as $classModel) {
            if ($classModel instanceof \App\Models\Classroom) {
                $classModel->unread_messages_count = $classModel->unreadMessagesCountForUser(Auth::id());
            }
        }

        return $classes;
    }

    public function refreshMessages()
    {
        $this->unreadCount = Message::unread(Auth::id())->count();
    }

    public function handleNewMessage($event)
    {
        // Kiểm tra xem tin nhắn có thuộc về cuộc trò chuyện hiện tại không
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

    // Xóa các biến liên quan đến thêm thành viên
    // public $addMemberSearch = '';
    // public function addMember($userId) { ... }

    public $addTeacherSearch = '';

    public function addTeacher($userId)
    {
        if (! $this->selectedClass) {
            return;
        }
        if (Auth::user()->role !== 'admin') {
            $this->dispatch('showToast', ['type' => 'error', 'message' => 'Chỉ admin mới được thêm giáo viên!']);

            return;
        }
        $user = User::find($userId);
        if ($user && $user->role === 'teacher' && ! $this->selectedClass->users->contains($user->id)) {
            $this->selectedClass->users()->attach($user->id, ['role' => 'teacher']);
            $this->selectedClass = Classroom::with('users')->find($this->selectedClass->id);
            $this->allUsers = User::all();
            $this->dispatch('showToast', ['type' => 'success', 'message' => 'Đã thêm giáo viên vào lớp!']);
        } else {
            $this->dispatch('showToast', ['type' => 'error', 'message' => 'Không thể thêm giáo viên!']);
        }
        $this->addTeacherSearch = '';
        $this->dispatch('closeAddTeacherModal');
    }

    public function removeMember($userId)
    {
        if (! $this->selectedClass) {
            return;
        }
        if (! in_array(auth()->user()->role, ['admin', 'teacher'])) {
            return;
        }
        if ($userId == auth()->id()) {
            return;
        }
        $user = User::find($userId);
        if ($user && $this->selectedClass->users->contains($user->id)) {
            $this->selectedClass->users()->detach($user->id);
            $this->selectedClass->refresh();
            $this->allUsers = User::all();
        }
        $this->memberSearch = '';
    }

    // Đóng modal thêm thành viên bằng JS
    public function dispatchCloseAddMemberModal()
    {
        $this->dispatch('closeAddMemberModal');
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
        if ($this->selectedClass) {
            Log::info('[Chat Debug] render: Thành viên hiện tại của lớp', [
                'class_id' => $this->selectedClass->id,
                'user_ids' => $this->selectedClass->users->pluck('id')->toArray(),
            ]);
            Log::info('[Chat Debug] render: allUsers', [
                'user_ids' => $this->allUsers ? $this->allUsers->pluck('id')->toArray() : null,
            ]);
            $canAdd = $this->allUsers->whereNotIn('id', $this->selectedClass->users->pluck('id'));
            Log::info('[Chat Debug] render: user có thể thêm', [
                'user_ids' => $canAdd->pluck('id')->toArray(),
            ]);
        }

        return view('admin.chat.index', [
            'messages' => $this->messages,
            'users' => $this->users,
            'classes' => $this->classes,
        ]);
    }
}
