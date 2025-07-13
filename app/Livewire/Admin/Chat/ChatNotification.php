<?php

namespace App\Livewire\Admin\Chat;

use App\Models\Message;
use Livewire\Component;

class ChatNotification extends Component
{
    public $unreadCount = 0;

    protected $listeners = ['messageReceived' => 'refreshCount'];

    public function mount()
    {
        $this->refreshCount();
    }

    public function refreshCount()
    {
        $this->unreadCount = Message::unread(auth()->id())->count();
    }

    public function render()
    {
        return view('livewire.admin.chat.chat-notification');
    }
}
