<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ClinicMessageNotification extends Notification
{
    use Queueable;

    public $message;
    public $clinic;

    public function __construct($message, $clinic)
    {
        $this->message = $message;
        $this->clinic = $clinic;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'sender' => $this->clinic->name,
            'clinic_id' => $this->clinic->id,
            'role' => 'clinic',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}
