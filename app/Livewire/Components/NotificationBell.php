<?php

namespace App\Livewire\Components;

use App\Models\Notification;
use Livewire\Component;

class NotificationBell extends Component
{
    public $unreadCount = 0;

    public $showDropdown = false;

    public $active = null;

    protected $listeners = ['notificationReceived' => 'refreshCount'];

    public function mount()
    {
        $this->refreshCount();
    }

    public function refreshCount()
    {
        $user = auth()->user();

        if ($user->role === 'teacher') {
            // Teacher chỉ thấy thông báo của các lớp họ đang dạy
            $classrooms = $user->teachingClassrooms;
            $this->unreadCount = Notification::whereIn('class_id', $classrooms->pluck('id'))
                ->where('is_read', false)
                ->count();
        } else {
            // Admin và Student thấy tất cả thông báo
            $this->unreadCount = Notification::where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhereNull('user_id');
            })->where('is_read', false)->count();
        }
    }

    public function toggleDropdown()
    {
        $this->showDropdown = ! $this->showDropdown;
    }

    public function markAsRead($id)
    {
        $user = auth()->user();

        if ($user->role === 'teacher') {
            // Teacher chỉ có thể đánh dấu đã đọc thông báo của các lớp họ đang dạy
            $classrooms = $user->teachingClassrooms;
            $notification = Notification::whereIn('class_id', $classrooms->pluck('id'))
                ->findOrFail($id);
        } else {
            // Admin và Student có thể đánh dấu tất cả thông báo
            $notification = Notification::where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhereNull('user_id');
            })->findOrFail($id);
        }

        $notification->markAsRead();
        $this->refreshCount();
    }

    public function getRecentNotificationsProperty()
    {
        $user = auth()->user();

        if ($user->role === 'teacher') {
            // Teacher chỉ thấy thông báo của các lớp họ đang dạy
            $classrooms = $user->teachingClassrooms;

            return Notification::whereIn('class_id', $classrooms->pluck('id'))
                ->with(['user', 'classroom'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } else {
            // Admin và Student thấy tất cả thông báo
            return Notification::where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhereNull('user_id');
            })
                ->with(['user', 'classroom'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }
    }

    public function render()
    {
        return view('components.notification-bell');
    }
}
