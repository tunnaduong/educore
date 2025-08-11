<?php

namespace App\Livewire\Student\Chat;

use App\Models\Message;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination, WithFileUploads;

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
        'fileDropped' => 'handleFileDrop'
    ];

    public function mount()
    {
        $this->unreadCount = Message::unread(auth()->id())->count();
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
                    'user_id' => auth()->id(),
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
        $this->validate([
            'messageText' => 'required|string|max:1000',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        $messageData = [
            'sender_id' => auth()->id(),
            'message' => $this->messageText,
        ];

        if ($this->messageType === 'user' && $this->selectedUser) {
            $messageData['receiver_id'] = $this->selectedUser->id;
        } elseif ($this->messageType === 'class' && $this->selectedClass) {
            $messageData['class_id'] = $this->selectedClass->id;
        }

        if ($this->attachment) {
            $path = $this->attachment->store('chat-attachments', 'public');
            $messageData['attachment'] = $path;
        }

        $message = Message::create($messageData);

        // Dispatch event để broadcast tin nhắn
        // \Illuminate\Support\Facades\Log::info('Dispatching MessageSent event', ['message_id' => $message->id]);
        // \App\Events\MessageSent::dispatch($message);

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
                $query->where('sender_id', auth()->id())
                    ->where('receiver_id', $this->selectedUser->id)
                    ->orWhere('sender_id', $this->selectedUser->id)
                    ->where('receiver_id', auth()->id());
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
        $query = User::where('id', '!=', auth()->id())
            ->whereIn('role', ['admin', 'teacher']);

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
            });
        }

        return $query->orderBy('name')->get();
    }

    public function getClassesProperty()
    {
        $query = Classroom::whereHas('users', function ($q) {
            $q->where('users.id', auth()->id());
        });

        if ($this->searchTerm) {
            $query->where('name', 'like', '%' . $this->searchTerm . '%');
        }

        $classes = $query->orderBy('name')->get();

        foreach ($classes as $class) {
            $class->unread_messages_count = $class->unreadMessagesCountForUser(auth()->id());
        }

        return $classes;
    }

    public function refreshMessages()
    {
        $this->unreadCount = Message::unread(auth()->id())->count();
    }

    public function handleNewMessage($event)
    {
        $message = $event['message'] ?? null;
        if ($message) {
            $isRelevant = false;

            if ($this->selectedUser && $message['receiver_id'] == $this->selectedUser->id && $message['sender_id'] == auth()->id()) {
                $isRelevant = true;
            } elseif ($this->selectedUser && $message['sender_id'] == $this->selectedUser->id && $message['receiver_id'] == auth()->id()) {
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
