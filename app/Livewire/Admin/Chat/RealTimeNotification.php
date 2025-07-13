<?php

namespace App\Livewire\Admin\Chat;

use App\Models\Message;
use Livewire\Component;

class RealTimeNotification extends Component
{
    public $unreadCount = 0;
    public $latestMessage = null;

    protected $listeners = ['echo:chat,MessageSent' => 'handleNewMessage'];

    public function mount()
    {
        $this->refreshData();
    }

    public function refreshData()
    {
        $this->unreadCount = Message::unread(auth()->id())->count();
        $this->latestMessage = Message::where('receiver_id', auth()->id())
            ->orWhere('class_id', function ($query) {
                $query->select('class_id')
                    ->from('class_user')
                    ->where('user_id', auth()->id());
            })
            ->latest()
            ->first();
    }

    public function handleNewMessage($event)
    {
        $this->refreshData();
        $this->dispatch('showNotification', [
            'title' => 'Tin nhắn mới',
            'message' => 'Bạn có tin nhắn mới từ ' . ($event['sender_name'] ?? 'người dùng khác'),
            'type' => 'info'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.chat.real-time-notification');
    }
}
