<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class DoctorMessageNotification extends Notification
{
    use Queueable;

    public $message;
    public $doctor;

    public function __construct($message, $doctor)
    {
        $this->message = $message;
        $this->doctor = $doctor;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'sender' => $this->doctor->name,
            'doctor_id' => $this->doctor->id,
            'role' => 'doctor',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}
