<?php

namespace App\Livewire\Teacher\Notifications;

use Livewire\Component;
use App\Models\Notification;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterType = '';
    public $filterStatus = '';
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingNotification = null;

    // Form fields
    public $title = '';
    public $message = '';
    public $type = 'info';
    public $class_id = '';
    public $scheduled_at = '';
    public $is_urgent = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'message' => 'required|string',
        'type' => 'required|in:info,warning,success,danger,reminder',
        'class_id' => 'nullable|exists:classrooms,id',
        'scheduled_at' => 'nullable|date|after:now',
        'is_urgent' => 'boolean',
    ];

    protected $messages = [
        'title.required' => 'Vui lòng nhập tiêu đề thông báo.',
        'message.required' => 'Vui lòng nhập nội dung thông báo.',
        'type.required' => 'Vui lòng chọn loại thông báo.',
        'class_id.exists' => 'Lớp học không tồn tại.',
        'scheduled_at.after' => 'Thời gian gửi phải sau thời gian hiện tại.',
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
        $this->class_id = '';
        $this->scheduled_at = '';
        $this->is_urgent = false;
        $this->editingNotification = null;
    }

    public function create()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function store()
    {
        $this->validate();

        $user = Auth::user();
        $classrooms = $user->teachingClassrooms;

        // Nếu không chọn lớp cụ thể, gửi cho tất cả lớp teacher đang dạy
        if (empty($this->class_id)) {
            foreach ($classrooms as $classroom) {
                Notification::create([
                    'title' => $this->title,
                    'message' => $this->message,
                    'type' => $this->type,
                    'class_id' => $classroom->id,
                    'user_id' => $user->id,
                    'scheduled_at' => $this->scheduled_at ?: now(),
                    'is_urgent' => $this->is_urgent,
                ]);
            }
        } else {
            // Kiểm tra xem teacher có quyền gửi thông báo cho lớp này không
            if ($classrooms->contains('id', $this->class_id)) {
                Notification::create([
                    'title' => $this->title,
                    'message' => $this->message,
                    'type' => $this->type,
                    'class_id' => $this->class_id,
                    'user_id' => $user->id,
                    'scheduled_at' => $this->scheduled_at ?: now(),
                    'is_urgent' => $this->is_urgent,
                ]);
            }
        }

        $this->showCreateModal = false;
        $this->resetForm();
        session()->flash('success', 'Đã tạo thông báo thành công!');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $classrooms = $user->teachingClassrooms;
        
        $this->editingNotification = Notification::whereIn('class_id', $classrooms->pluck('id'))
            ->findOrFail($id);

        $this->title = $this->editingNotification->title;
        $this->message = $this->editingNotification->message;
        $this->type = $this->editingNotification->type;
        $this->class_id = $this->editingNotification->class_id;
        $this->scheduled_at = $this->editingNotification->scheduled_at?->format('Y-m-d\TH:i');
        $this->is_urgent = $this->editingNotification->is_urgent;

        $this->showEditModal = true;
    }

    public function update()
    {
        $this->validate();

        if ($this->editingNotification) {
            $this->editingNotification->update([
                'title' => $this->title,
                'message' => $this->message,
                'type' => $this->type,
                'class_id' => $this->class_id,
                'scheduled_at' => $this->scheduled_at ?: now(),
                'is_urgent' => $this->is_urgent,
            ]);
        }

        $this->showEditModal = false;
        $this->resetForm();
        session()->flash('success', 'Đã cập nhật thông báo thành công!');
    }

    public function delete($id)
    {
        $user = Auth::user();
        $classrooms = $user->teachingClassrooms;
        
        $notification = Notification::whereIn('class_id', $classrooms->pluck('id'))
            ->findOrFail($id);
        
        $notification->delete();
        session()->flash('success', 'Đã xóa thông báo thành công!');
    }

    public function duplicate($id)
    {
        $user = Auth::user();
        $classrooms = $user->teachingClassrooms;
        
        $original = Notification::whereIn('class_id', $classrooms->pluck('id'))
            ->findOrFail($id);

        $this->title = $original->title . ' (Bản sao)';
        $this->message = $original->message;
        $this->type = $original->type;
        $this->class_id = $original->class_id;
        $this->scheduled_at = '';
        $this->is_urgent = $original->is_urgent;

        $this->showCreateModal = true;
    }

    public function sendNow($id)
    {
        $user = Auth::user();
        $classrooms = $user->teachingClassrooms;
        
        $notification = Notification::whereIn('class_id', $classrooms->pluck('id'))
            ->findOrFail($id);
        
        $notification->update(['scheduled_at' => now()]);
        session()->flash('success', 'Đã gửi thông báo ngay!');
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        $classrooms = $user->teachingClassrooms;
        
        Notification::whereIn('class_id', $classrooms->pluck('id'))
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        session()->flash('success', 'Đã đánh dấu tất cả thông báo đã đọc!');
    }

    public function toggleRead($id)
    {
        $user = Auth::user();
        $classrooms = $user->teachingClassrooms;
        
        $notification = Notification::whereIn('class_id', $classrooms->pluck('id'))
            ->findOrFail($id);
        
        $notification->update(['is_read' => !$notification->is_read]);
        
        $status = $notification->is_read ? 'đã đọc' : 'chưa đọc';
        session()->flash('success', "Đã đánh dấu thông báo {$status}!");
    }

    public function deleteExpired()
    {
        $user = Auth::user();
        $classrooms = $user->teachingClassrooms;
        
        $deleted = Notification::whereIn('class_id', $classrooms->pluck('id'))
            ->where('scheduled_at', '<', now()->subDays(30))
            ->delete();
        
        session()->flash('success', "Đã xóa {$deleted} thông báo hết hạn!");
    }

    public function render()
    {
        $user = Auth::user();
        $classrooms = $user->teachingClassrooms;

        $query = Notification::whereIn('class_id', $classrooms->pluck('id'))
            ->with(['classroom', 'user']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                  ->orWhere('message', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        if ($this->filterStatus === 'read') {
            $query->where('is_read', true);
        } elseif ($this->filterStatus === 'unread') {
            $query->where('is_read', false);
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('teacher.notifications.index', [
            'notifications' => $notifications,
            'classrooms' => $classrooms,
        ]);
    }
} 