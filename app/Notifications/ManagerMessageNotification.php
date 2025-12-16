<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ManagerMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $message;
    public $manager;

    public function __construct($message, $manager)
    {
        $this->message = $message;
        $this->manager = $manager;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'sender' => $this->manager->name,
            'manager_id' => $this->manager->id,
            'role' => (has_role('manager')) ? 'manager' : 'consultant',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}