<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\PatientDocument;

class PatientDocumentMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;
    public string $pdfPath;
    public PatientDocument $document;
    public bool $isProtected;

    public function __construct(
        array $data,
        string $pdfPath,
        PatientDocument $document,
        bool $isProtected = false
    ) {
        $this->data = $data;
        $this->pdfPath = $pdfPath;
        $this->document = $document;
        $this->isProtected = $isProtected;
    }

    public function build()
    {
        $messageBody = $this->data['message'] ?? '';
        $subject = $this->data['subject'] ?? 'Patient Document';

        \Log::info('Building patient document email');

        $mail = $this->subject($subject)
            ->view('emails.patient-document')
            ->with([
                'messageBody' => $messageBody,
                'documentName' => $this->document->title ?? 'Document',
                'isProtected' => $this->isProtected,
            ])
            ->attach($this->pdfPath, [
                'as' => ($this->document->title ?? 'document') . '.pdf',
                'mime' => 'application/pdf',
            ]);

        // Set FROM only if provided
        if (!empty($this->data['sender_email'])) {
            $mail->from($this->data['sender_email']);
        }

        return $mail;
    }
}