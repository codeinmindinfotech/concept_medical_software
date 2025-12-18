<?php

namespace App\Events;

use App\Models\Chatmessages;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageSent
{
    public $message;

    public function __construct(Chatmessages $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel(
            'conversation.' . $this->message->conversation_id
        );
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }
}