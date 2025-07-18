<?php

namespace App\Livewire\Admin\Chat;

use App\Models\Message;
use App\Models\User;
use App\Models\Classroom;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithPagination, WithFileUploads;

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
        'messageReceived' => 'refreshMessages'
    ];

    public function mount()
    {
        $this->unreadCount = Message::unread(auth()->id())->count();
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
        \Log::info('[Chat Debug] selectClass: Chọn lớp', [
            'class_id' => $classId,
            'selectedClass' => $this->selectedClass ? $this->selectedClass->toArray() : null,
            'current_user_id' => auth()->id(),
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
        \Log::info('Dispatching MessageSent event', ['message_id' => $message->id]);
        \App\Events\MessageSent::dispatch($message);

        $this->messageText = '';
        $this->attachment = null;
        $this->dispatch('messageSent');
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
        $query = User::where('id', '!=', auth()->id());

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
        // Nếu là admin, hiển thị tất cả lớp học
        if (auth()->user()->role === 'admin') {
            $query = Classroom::query();
        } else {
            // Nếu không phải admin, chỉ hiển thị lớp mà user được gán
            $query = Classroom::whereHas('users', function ($q) {
                $q->where('users.id', auth()->id());
            });
        }

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
        // Kiểm tra xem tin nhắn có thuộc về cuộc trò chuyện hiện tại không
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

    // Xóa các biến liên quan đến thêm thành viên
    // public $addMemberSearch = '';
    // public function addMember($userId) { ... }

    public $addTeacherSearch = '';

    public function addTeacher($userId)
    {
        if (!$this->selectedClass)
            return;
        if (auth()->user()->role !== 'admin') {
            $this->dispatch('showToast', ['type' => 'error', 'message' => 'Chỉ admin mới được thêm giáo viên!']);
            return;
        }
        $user = User::find($userId);
        if ($user && $user->role === 'teacher' && !$this->selectedClass->users->contains($user->id)) {
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
        if (!$this->selectedClass)
            return;
        if (!in_array(auth()->user()->role, ['admin', 'teacher']))
            return;
        if ($userId == auth()->id())
            return;
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

    public function render()
    {
        if ($this->selectedClass) {
            \Log::info('[Chat Debug] render: Thành viên hiện tại của lớp', [
                'class_id' => $this->selectedClass->id,
                'user_ids' => $this->selectedClass->users->pluck('id')->toArray(),
            ]);
            \Log::info('[Chat Debug] render: allUsers', [
                'user_ids' => $this->allUsers ? $this->allUsers->pluck('id')->toArray() : null,
            ]);
            $canAdd = $this->allUsers->whereNotIn('id', $this->selectedClass->users->pluck('id'));
            \Log::info('[Chat Debug] render: user có thể thêm', [
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
