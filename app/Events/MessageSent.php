<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        \Log::info('Broadcasting message', [
            'message_id' => $this->message->id,
            'class_id' => $this->message->class_id,
            'sender_id' => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id
        ]);

        if ($this->message->class_id) {
            // Broadcast to class channel
            return [
                new Channel('chat-class-' . $this->message->class_id)
            ];
        } else {
            // Broadcast to private user channels
            return [
                new PrivateChannel('chat-user-' . $this->message->sender_id),
                new PrivateChannel('chat-user-' . $this->message->receiver_id)
            ];
        }
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'message' => $this->message->message,
                'sender_id' => $this->message->sender_id,
                'receiver_id' => $this->message->receiver_id,
                'class_id' => $this->message->class_id,
                'attachment' => $this->message->attachment,
                'created_at' => $this->message->created_at->toISOString(),
                'sender' => [
                    'id' => $this->message->sender->id,
                    'name' => $this->message->sender->name,
                    'avatar' => $this->message->sender->avatar,
                ]
            ]
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}