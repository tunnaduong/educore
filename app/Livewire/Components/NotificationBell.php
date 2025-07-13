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
        $this->unreadCount = Notification::where(function($query) {
            $query->where('user_id', auth()->id())
                  ->orWhereNull('user_id');
        })->where('is_read', false)->count();
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function markAsRead($id)
    {
        $notification = Notification::where(function($query) {
            $query->where('user_id', auth()->id())
                  ->orWhereNull('user_id');
        })->findOrFail($id);
        
        $notification->markAsRead();
        $this->refreshCount();
    }

    public function getRecentNotificationsProperty()
    {
        return Notification::where(function($query) {
            $query->where('user_id', auth()->id())
                  ->orWhereNull('user_id');
        })
        ->with(['user', 'classroom'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    }

    public function render()
    {
        return view('components.notification-bell');
    }
} 