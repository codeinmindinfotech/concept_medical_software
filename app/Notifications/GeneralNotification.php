<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class GeneralNotification extends Notification
{
    public $message;
    public $sender;
    public $companyId;

    public function __construct($message, $sender, $companyId)
    {
        $this->message = $message;
        $this->sender = $sender;
        $this->companyId = $companyId;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'sender' => $this->sender,
            'company_id' => $this->companyId,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => $this->message,
            'sender' => $this->sender,
            'company_id' => $this->companyId,
        ]);
    }

}

