<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class PatientMessageNotification extends Notification
{
    use Queueable;

    public $message;
    public $patient;

    public function __construct($message, $patient)
    {
        $this->message = $message;
        $this->patient = $patient;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'sender' => $this->patient->name,
            'patient_id' => $this->patient->id,
            'role' => 'patient',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}
