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
        Notification::where(function($query) {
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

    public function getNotificationsProperty()
    {
        $query = Notification::with(['classroom'])
            ->where(function($query) {
                $query->where('user_id', auth()->id())
                      ->orWhereNull('user_id');
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
        return Notification::where(function($query) {
            $query->where('user_id', auth()->id())
                  ->orWhereNull('user_id');
        })->where('is_read', false)->count();
    }

    public function render()
    {
        return view('student.notifications.index', [
            'notifications' => $this->notifications,
        ])->layout('components.layouts.dash-student', ['active' => 'notifications']);
    }
} 