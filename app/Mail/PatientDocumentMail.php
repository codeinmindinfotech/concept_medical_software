<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\PatientDocument;
class PatientDocumentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $pdfPath;
    public $document;

    public function __construct($data, $pdfPath, PatientDocument $document)
    {
        $this->data = $data;
        $this->pdfPath = $pdfPath;
        $this->document = $document;
    }

    public function build()
    {
        $message = $this->data['message'] ?? '';
        \Log::info('Building email: ' . $message);

        return $this->from($this->data['sender_email'])
            ->subject($this->data['subject'])
            ->view('emails.patient-document')
            ->with([
                'messageBody' => $message,
                'documentName' => "documentName",//$this->document->name,
            ])
            ->attach($this->pdfPath, [
                'as' => 'document.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
