<?php
namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $notification;
    public $recipientId;
    public $recipientModel; // Add this property

    /**
     * @param  mixed  $notification  The Notification instance
     * @param  int|string  $recipientId  Recipient model's primary key
     * @param  mixed  $recipientModel  The actual recipient model instance
     */
    public function __construct($notification, $recipientId, $recipientModel)
    {
        $this->notification = $notification;
        $this->recipientId = $recipientId;
        $this->recipientModel = $recipientModel; // Initialize the recipient model
    }

    public function broadcastOn()
    {
        $className = strtolower(class_basename($this->recipientModel)); // patient, doctor, clinic, user
        return new PrivateChannel("{$className}.{$this->recipientId}");
    }

    public function broadcastAs()
    {
        return 'notification.received';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->notification->id,
            'data' => $this->notification->data,
            'read_at' => $this->notification->read_at,
            'created_at' => $this->notification->created_at->toDateTimeString(),
        ];
    }
}