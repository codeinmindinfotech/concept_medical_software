<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PatientDocumentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $patient;
    public $documents; // array of file paths
    public $messageContent; // optional body content

    /**
     * Create a new message instance.
     */
    public function __construct($patient, $documents = [], $messageContent = null)
    {
        $this->patient = $patient;
        $this->documents = $documents;
        $this->messageContent = $messageContent;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $email = $this->subject("Your Documents from Concept Medical System")
                      ->view('emails.patient_documents')
                      ->with([
                          'patient' => $this->patient,
                          'messageContent' => $this->messageContent
                      ]);
    
        // Attach documents if any
        foreach ($this->documents as $doc) {
            // $doc is a DocumentTemplate model
            $filePath = storage_path('app/public/' . $doc->file_path);
    
            if (file_exists($filePath)) {
                $email->attach($filePath, [
                    'as' => $doc->name . '.' . pathinfo($doc->file_path, PATHINFO_EXTENSION),
                    'mime' => \File::mimeType($filePath),
                ]);
            } else {
                \Log::error("Attachment file not found: " . $filePath);
            }
        }
    
        return $email;
    }
    
}
