<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $messageBody;

    public function __construct($messageBody)
    {
        $this->messageBody = $messageBody;
    }

    public function build()
    {
        return $this->subject('You have a new notification')
                    ->view('emails.notification')
                    ->with(['body' => $this->messageBody]);
    }
}