<?php

namespace App\Livewire\Teacher\Chat;

use App\Models\Message;
use App\Models\User;
use App\Models\Classroom;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $selectedUser = null;
    public $selectedClass = null;
    public $messageText = '';
    public $attachment = null;
    public $searchTerm = '';
    public $messageType = 'class'; // 'user' or 'class'
    public $unreadCount = 0;
    public $memberSearch = '';
    public $allUsers;
    public $activeTab = 'classes'; // 'users' hoặc 'classes'
    public $isDragging = false;
    public $typingUsers = [];
    public $isTyping = false;
    public $typingTimeout = null;

    protected $listeners = [
        'messageReceived' => 'refreshMessages',
        'fileDropped' => 'handleFileDrop',
        'echo:chat-class-*,message.sent' => 'handleNewMessage',
        'echo-private:chat-user-*,message.sent' => 'handleNewMessage',
        'userTyping' => 'handleUserTyping',
        'userStoppedTyping' => 'handleUserStoppedTyping'
    ];

    public function mount()
    {
        $userId = Auth::id();
        if ($userId) {
            $this->unreadCount = Message::unread($userId)->count();
        }
        $this->allUsers = User::where('role', '!=', 'admin')->get();
    }

    public function selectUser($userId)
    {
        $this->selectedUser = User::find($userId);
        $this->selectedClass = null;
        $this->messageType = 'user';
        $this->activeTab = 'users';
        $this->resetPage();
        
        // Đánh dấu đã đọc tin nhắn 1-1
        $currentUserId = Auth::id();
        if ($currentUserId && $this->selectedUser) {
            Message::where('sender_id', $userId)
                ->where('receiver_id', $currentUserId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }
    }

    public function selectClass($classId)
    {
        $this->selectedClass = Classroom::with('users')->find($classId);
        $currentUserId = Auth::id();
        
        Log::info('[Chat Debug] selectClass: Chọn lớp', [
            'class_id' => $classId,
            'selectedClass' => $this->selectedClass ? $this->selectedClass->toArray() : null,
            'current_user_id' => $currentUserId,
        ]);
        
        $this->selectedUser = null;
        $this->messageType = 'class';
        $this->activeTab = 'classes';
        $this->resetPage();
        
        // Đánh dấu đã đọc tin nhắn nhóm
        if ($currentUserId) {
            $lastMsg = Message::where('class_id', $classId)->latest('id')->first();
            if ($lastMsg) {
                \App\Models\ClassroomMessageRead::updateOrCreate(
                    [
                        'user_id' => $currentUserId,
                        'class_id' => $classId,
                    ],
                    [
                        'last_read_message_id' => $lastMsg->id,
                        'last_read_at' => now(),
                    ]
                );
            }
        }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function sendMessage()
    {
        $this->validate([
            'messageText' => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        if ((trim($this->messageText) === '' || $this->messageText === null) && !$this->attachment) {
            $this->addError('messageText', 'Vui lòng nhập nội dung hoặc chọn tệp đính kèm.');
            return;
        }

        $currentUserId = Auth::id();
        if (!$currentUserId) {
            return;
        }

        $messageData = [
            'sender_id' => $currentUserId,
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
        Log::info('Dispatching MessageSent event', ['message_id' => $message->id]);
        \App\Events\MessageSent::dispatch($message);

        $this->messageText = '';
        $this->attachment = null;
        $this->dispatch('messageSent');
        
        // Dừng typing indicator
        $this->stopTyping();
    }

    public function handleFileDrop($fileData)
    {
        // Xử lý file được kéo thả
        if (isset($fileData['file'])) {
            $this->attachment = $fileData['file'];
        }
    }

    public function startTyping()
    {
        if (!$this->isTyping) {
            $this->isTyping = true;
            $currentUser = Auth::user();
            $currentUserId = Auth::id();
            
            if (!$currentUser || !$currentUserId) {
                return;
            }
            
            if ($this->messageType === 'user' && $this->selectedUser) {
                $this->dispatch('userTyping', [
                    'userId' => $currentUserId,
                    'userName' => $currentUser->name,
                    'receiverId' => $this->selectedUser->id
                ]);
            } elseif ($this->messageType === 'class' && $this->selectedClass) {
                $this->dispatch('userTyping', [
                    'userId' => $currentUserId,
                    'userName' => $currentUser->name,
                    'classId' => $this->selectedClass->id
                ]);
            }
        }
    }

    public function stopTyping()
    {
        if ($this->isTyping) {
            $this->isTyping = false;
            $currentUserId = Auth::id();
            
            if (!$currentUserId) {
                return;
            }
            
            if ($this->messageType === 'user' && $this->selectedUser) {
                $this->dispatch('userStoppedTyping', [
                    'userId' => $currentUserId,
                    'receiverId' => $this->selectedUser->id
                ]);
            } elseif ($this->messageType === 'class' && $this->selectedClass) {
                $this->dispatch('userStoppedTyping', [
                    'userId' => $currentUserId,
                    'classId' => $this->selectedClass->id
                ]);
            }
        }
    }

    public function handleUserTyping($event)
    {
        $userId = $event['userId'] ?? null;
        $userName = $event['userName'] ?? null;
        $currentUserId = Auth::id();
        
        if ($userId && $currentUserId && $userId != $currentUserId) {
            $this->typingUsers[$userId] = $userName;
        }
    }

    public function handleUserStoppedTyping($event)
    {
        $userId = $event['userId'] ?? null;
        $currentUserId = Auth::id();
        
        if ($userId && $currentUserId && $userId != $currentUserId) {
            unset($this->typingUsers[$userId]);
        }
    }

    public function getMessagesProperty()
    {
        $currentUserId = Auth::id();
        if (!$currentUserId) {
            return collect();
        }

        if ($this->selectedUser) {
            return Message::where(function ($query) use ($currentUserId) {
                $query->where('sender_id', $currentUserId)
                    ->where('receiver_id', $this->selectedUser->id)
                    ->orWhere('sender_id', $this->selectedUser->id)
                    ->where('receiver_id', $currentUserId);
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
        $currentUserId = Auth::id();
        if (!$currentUserId) {
            return collect();
        }

        $query = User::where('id', '!=', $currentUserId)
            ->whereIn('role', ['student', 'teacher']);

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
        $currentUserId = Auth::id();
        if (!$currentUserId) {
            return collect();
        }

        // Giáo viên chỉ thấy lớp mà họ được gán
        $query = Classroom::whereHas('users', function ($q) use ($currentUserId) {
            $q->where('users.id', $currentUserId);
        });

        if ($this->searchTerm) {
            $query->where('name', 'like', '%' . $this->searchTerm . '%');
        }

        $classes = $query->orderBy('name')->get();

        foreach ($classes as $class) {
            $class->unread_messages_count = $class->unreadMessagesCountForUser($currentUserId);
        }
        return $classes;
    }

    public function refreshMessages()
    {
        $currentUserId = Auth::id();
        if ($currentUserId) {
            $this->unreadCount = Message::unread($currentUserId)->count();
        }
    }

    public function handleNewMessage($event)
    {
        // Kiểm tra xem tin nhắn có thuộc về cuộc trò chuyện hiện tại không
        $message = $event['message'] ?? null;
        $currentUserId = Auth::id();
        
        if ($message && $currentUserId) {
            $isRelevant = false;

            if ($this->selectedUser && $message['receiver_id'] == $this->selectedUser->id && $message['sender_id'] == $currentUserId) {
                $isRelevant = true;
            } elseif ($this->selectedUser && $message['sender_id'] == $this->selectedUser->id && $message['receiver_id'] == $currentUserId) {
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
        $currentUserId = Auth::id();
        
        if ($this->selectedClass && $currentUserId) {
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
        
        return view('teacher.chat.index', [
            'messages' => $this->messages,
            'users' => $this->users,
            'classes' => $this->classes,
        ]);
    }
}
