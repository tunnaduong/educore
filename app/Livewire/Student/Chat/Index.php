<?php

namespace App\Livewire\Student\Chat;

use App\Models\Message;
use App\Models\User;
use App\Models\Classroom;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $selectedTeacher = null;
    public $selectedClass = null;
    public $messageText = '';
    public $attachment = null;
    public $searchTerm = '';
    public $unreadCount = 0;
    public $activeTab = 'teachers'; // 'teachers' hoặc 'classes'

    protected $listeners = [
        'messageReceived' => 'refreshMessages'
    ];

    public function mount()
    {
        $this->unreadCount = Message::unread(Auth::id())->count();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->selectedTeacher = null;
        $this->selectedClass = null;
        $this->resetPage();
    }

    public function selectTeacher($teacherId)
    {
        $this->selectedTeacher = User::find($teacherId);
        $this->selectedClass = null;
        $this->activeTab = 'teachers';
        $this->resetPage();
        
        // Đánh dấu đã đọc tin nhắn từ giáo viên này
        Message::where('sender_id', $teacherId)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function selectClass($classId)
    {
        $this->selectedClass = Classroom::with('users')->find($classId);
        $this->selectedTeacher = null;
        $this->activeTab = 'classes';
        $this->resetPage();
        
        // Đánh dấu đã đọc tin nhắn lớp
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

    public function sendMessage()
    {
        $this->validate([
            'messageText' => 'required|string|max:1000',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        $messageData = [
            'sender_id' => Auth::id(),
            'message' => $this->messageText,
        ];

        if ($this->selectedTeacher) {
            $messageData['receiver_id'] = $this->selectedTeacher->id;
        } elseif ($this->selectedClass) {
            $messageData['class_id'] = $this->selectedClass->id;
        }

        if ($this->attachment) {
            $path = $this->attachment->store('chat-attachments', 'public');
            $messageData['attachment'] = $path;
        }

        $message = Message::create($messageData);

        \App\Events\MessageSent::dispatch($message);

        $this->messageText = '';
        $this->attachment = null;
        $this->dispatch('messageSent');
    }

    public function getMessagesProperty()
    {
        if ($this->selectedTeacher) {
            return Message::where(function ($query) {
                $query->where('sender_id', Auth::id())
                    ->where('receiver_id', $this->selectedTeacher->id)
                    ->orWhere('sender_id', $this->selectedTeacher->id)
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

    public function getTeachersProperty()
    {
        $query = User::where('role', 'teacher');

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
            });
        }

        $teachers = $query->orderBy('name')->get();

        // Tính số tin nhắn chưa đọc cho mỗi giáo viên
        foreach ($teachers as $teacher) {
            $teacher->unread_messages_count = Message::where('sender_id', $teacher->id)
                ->where('receiver_id', Auth::id())
                ->whereNull('read_at')
                ->count();
        }

        return $teachers;
    }

    public function getClassesProperty()
    {
        $loggedInUser = Auth::user();
        $query = Classroom::whereHas('users', function ($q) use ($loggedInUser) {
            $q->where('users.id', $loggedInUser->id);
        });

        if ($this->searchTerm) {
            $query->where('name', 'like', '%' . $this->searchTerm . '%');
        }

        $classes = $query->with('users')->orderBy('name')->get();

        // Tính số tin nhắn chưa đọc cho mỗi lớp
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

            // Kiểm tra tin nhắn 1-1 với giáo viên
            if ($this->selectedTeacher && $message['receiver_id'] == $this->selectedTeacher->id && $message['sender_id'] == Auth::id()) {
                $isRelevant = true;
            } elseif ($this->selectedTeacher && $message['sender_id'] == $this->selectedTeacher->id && $message['receiver_id'] == Auth::id()) {
                $isRelevant = true;
            }
            
            // Kiểm tra tin nhắn nhóm lớp
            elseif ($this->selectedClass && $message['class_id'] == $this->selectedClass->id) {
                $isRelevant = true;
            }

            if ($isRelevant) {
                $this->refreshMessages();
                $this->dispatch('messageReceived');
            }
        }
    }

    public function render()
    {
        return view('student.chat.index', [
            'messages' => $this->messages,
            'teachers' => $this->teachers,
            'classes' => $this->classes,
        ]);
    }
}
