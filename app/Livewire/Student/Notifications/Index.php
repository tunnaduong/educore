<?php

namespace App\Livewire\Student\Notifications;

use App\Models\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterType = '';
    public $filterStatus = '';
    public $selectedNotification = null;

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->orWhereNull('user_id')
            ->findOrFail($id);

        $notification->markAsRead();
        session()->flash('message', 'Đã đánh dấu thông báo là đã đọc!');
    }

    public function markAllAsRead()
    {
        Notification::where(function ($query) {
            $query->where('user_id', auth()->id())
                ->orWhereNull('user_id');
        })->where('is_read', false)->update(['is_read' => true]);

        session()->flash('message', 'Đã đánh dấu tất cả thông báo là đã đọc!');
    }

    public function delete($id)
    {
        $notification = Notification::findOrFail($id);

        // Chỉ cho phép xóa thông báo của chính mình
        if ($notification->user_id === auth()->id()) {
            $notification->delete();
            session()->flash('message', 'Thông báo đã được xóa thành công!');
        } else {
            session()->flash('error', 'Bạn không có quyền xóa thông báo này!');
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterType = '';
        $this->filterStatus = '';
    }

    public function showNotification($id)
    {
        $this->selectedNotification = Notification::with(['classroom'])
            ->where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhereNull('user_id');
            })
            ->findOrFail($id);
    }

    public function closeNotification()
    {
        $this->selectedNotification = null;
    }

    public function getNotificationsProperty()
    {
        $query = Notification::with(['classroom'])
            ->where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhereNull('user_id');
            })
            // Chỉ hiển thị thông báo đã đến lịch gửi (scheduled_at <= now hoặc null)
            ->where(function ($query) {
                $query->whereNull('scheduled_at')
                    ->orWhere('scheduled_at', '<=', now());
            })
            // Chỉ hiển thị thông báo còn hạn (expires_at > now hoặc null)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
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

    public function getUnreadCountProperty()
    {
        return Notification::where(function ($query) {
            $query->where('user_id', auth()->id())
                ->orWhereNull('user_id');
        })
            // Chỉ đếm thông báo đã đến lịch gửi
            ->where(function ($query) {
                $query->whereNull('scheduled_at')
                    ->orWhere('scheduled_at', '<=', now());
            })
            // Chỉ đếm thông báo còn hạn
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->where('is_read', false)->count();
    }

    public function render()
    {
        return view('student.notifications.index', [
            'notifications' => $this->notifications,
        ]);
    }
}