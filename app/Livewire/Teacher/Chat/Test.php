<?php

namespace App\Livewire\Teacher\Chat;

use App\Models\Classroom;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Test extends Component
{
    use WithFileUploads;

    public $testMessage = '';

    public $testAttachment = null;

    public $selectedTestUser = null;

    public $selectedTestClass = null;

    public function mount()
    {
        // Lấy user đầu tiên để test
        $this->selectedTestUser = User::where('role', 'student')->first();
        $this->selectedTestClass = Classroom::first();
    }

    public function sendTestMessage()
    {
        $this->validate([
            'testMessage' => 'required|string|max:1000',
        ]);

        $currentUserId = Auth::id();
        if (! $currentUserId) {
            return;
        }

        $messageData = [
            'sender_id' => $currentUserId,
            'message' => $this->testMessage,
        ];

        if ($this->selectedTestUser) {
            $messageData['receiver_id'] = $this->selectedTestUser->id;
        } elseif ($this->selectedTestClass) {
            $messageData['class_id'] = $this->selectedTestClass->id;
        }

        if ($this->testAttachment) {
            $path = $this->testAttachment->store('chat-attachments', 'public');
            $messageData['attachment'] = $path;
        }

        $message = Message::create($messageData);

        // Dispatch event để broadcast tin nhắn
        \App\Events\MessageSent::dispatch($message);

        $this->testMessage = '';
        $this->testAttachment = null;

        $this->dispatch('showToast', ['type' => 'success', 'message' => 'Tin nhắn test đã được gửi!']);
    }

    public function render()
    {
        return view('teacher.chat.test');
    }
}
