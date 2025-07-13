<?php

namespace App\Livewire\Admin\Notifications;

use App\Models\Notification;
use App\Models\Classroom;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $filterType = '';
    public $filterStatus = '';
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $selectedNotification;

    // Form fields
    public $title = '';
    public $message = '';
    public $type = 'info';
    public $user_id = '';
    public $class_id = '';
    public $scheduled_at = '';
    public $expires_at = '';
    public $is_global = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'message' => 'required|string',
        'type' => 'required|in:info,warning,success,danger,reminder',
        'user_id' => 'nullable|exists:users,id',
        'class_id' => 'nullable|exists:classrooms,id',
        'scheduled_at' => 'nullable|date|after:now',
        'expires_at' => 'nullable|date|after:scheduled_at',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->title = '';
        $this->message = '';
        $this->type = 'info';
        $this->user_id = '';
        $this->class_id = '';
        $this->scheduled_at = '';
        $this->expires_at = '';
        $this->is_global = false;
        $this->resetValidation();
    }

    public function create()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'scheduled_at' => $this->scheduled_at ? now()->parse($this->scheduled_at) : null,
            'expires_at' => $this->expires_at ? now()->parse($this->expires_at) : null,
        ];

        if ($this->is_global) {
            // Tạo thông báo cho tất cả users
            $users = User::where('role', 'student')->get();
            foreach ($users as $user) {
                Notification::create(array_merge($data, [
                    'user_id' => $user->id,
                    'class_id' => $this->class_id ?: null,
                ]));
            }
        } else {
            // Tạo thông báo cho user cụ thể
            $data['user_id'] = $this->user_id ?: null;
            $data['class_id'] = $this->class_id ?: null;
            Notification::create($data);
        }

        $this->showCreateModal = false;
        $this->resetForm();
        session()->flash('message', 'Thông báo đã được tạo thành công!');
    }

    public function edit($id)
    {
        $notification = Notification::findOrFail($id);
        $this->selectedNotification = $notification;
        
        $this->title = $notification->title;
        $this->message = $notification->message;
        $this->type = $notification->type;
        $this->user_id = $notification->user_id;
        $this->class_id = $notification->class_id;
        $this->scheduled_at = $notification->scheduled_at?->format('Y-m-d\TH:i');
        $this->expires_at = $notification->expires_at?->format('Y-m-d\TH:i');
        $this->is_global = false; // Reset global flag for edit
        
        $this->showEditModal = true;
    }

    public function update()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'scheduled_at' => $this->scheduled_at ? now()->parse($this->scheduled_at) : null,
            'expires_at' => $this->expires_at ? now()->parse($this->expires_at) : null,
        ];

        if ($this->is_global) {
            // Cập nhật thông báo cho tất cả users
            $users = User::where('role', 'student')->get();
            foreach ($users as $user) {
                Notification::updateOrCreate(
                    ['id' => $this->selectedNotification->id],
                    array_merge($data, [
                        'user_id' => $user->id,
                        'class_id' => $this->class_id ?: null,
                    ])
                );
            }
        } else {
            // Cập nhật thông báo cho user cụ thể
            $data['user_id'] = $this->user_id ?: null;
            $data['class_id'] = $this->class_id ?: null;
            $this->selectedNotification->update($data);
        }

        $this->showEditModal = false;
        $this->resetForm();
        session()->flash('message', 'Thông báo đã được cập nhật thành công!');
    }

    public function delete($id)
    {
        $this->selectedNotification = Notification::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function confirmDelete()
    {
        if ($this->selectedNotification) {
            // Nếu là thông báo global, xóa tất cả thông báo có cùng title và message
            if ($this->selectedNotification->user_id === null) {
                Notification::where('title', $this->selectedNotification->title)
                    ->where('message', $this->selectedNotification->message)
                    ->where('type', $this->selectedNotification->type)
                    ->delete();
            } else {
                $this->selectedNotification->delete();
            }
        }
        
        $this->showDeleteModal = false;
        $this->selectedNotification = null;
        session()->flash('message', 'Thông báo đã được xóa thành công!');
    }

    public function toggleRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();
        session()->flash('message', 'Đã đánh dấu thông báo là đã đọc!');
    }

    public function duplicate($id)
    {
        $notification = Notification::findOrFail($id);
        
        $this->title = $notification->title . ' (Bản sao)';
        $this->message = $notification->message;
        $this->type = $notification->type;
        $this->user_id = $notification->user_id;
        $this->class_id = $notification->class_id;
        $this->scheduled_at = '';
        $this->expires_at = '';
        $this->is_global = false;
        
        $this->showCreateModal = true;
        session()->flash('message', 'Đã sao chép thông báo vào form tạo mới!');
    }

    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);
        session()->flash('message', 'Đã đánh dấu tất cả thông báo là đã đọc!');
    }

    public function deleteExpired()
    {
        $count = Notification::where('expires_at', '<', now())->count();
        Notification::where('expires_at', '<', now())->delete();
        session()->flash('message', "Đã xóa {$count} thông báo hết hạn!");
    }

    public function sendNow($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['scheduled_at' => now()]);
        session()->flash('message', 'Thông báo sẽ được gửi ngay!');
    }

    public function getNotificationsProperty()
    {
        $query = Notification::with(['user', 'classroom'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('message', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterType, function ($query) {
                $query->where('type', $this->filterType);
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('is_read', $this->filterStatus === 'read');
            })
            ->orderBy('created_at', 'desc');

        return $query->paginate(10);
    }

    public function getUsersProperty()
    {
        return User::where('role', 'student')->get();
    }

    public function getClassroomsProperty()
    {
        return Classroom::all();
    }

    public function render()
    {
        return view('admin.notifications.index', [
            'notifications' => $this->notifications,
            'users' => $this->users,
            'classrooms' => $this->classrooms,
        ]);
    }
} 