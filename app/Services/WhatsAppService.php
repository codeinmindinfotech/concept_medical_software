<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Company;
use App\Models\Message;

class WhatsAppService
{
    protected $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    protected function apiUrl($endpoint)
    {
        return "https://graph.facebook.com/v21.0/{$this->company->whatsapp_phone_number_id}/{$endpoint}";
    }

    protected function headers()
    {
        return [
            'Authorization' => "Bearer {$this->company->whatsapp_access_token}",
            'Content-Type' => 'application/json',
        ];
    }

    public function sendTextMessage($to, $text)
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'text',
            'text' => ['body' => $text],
        ];

        $response = Http::withHeaders($this->headers())
            ->post("https://graph.facebook.com/v21.0/{$this->company->whatsapp_phone_number_id}/messages", $payload)
            ->json();

        // Store log
        Message::create([
            'company_id' => $this->company->id,
            'to' => $to,
            'direction' => 'outgoing',
            'type' => 'text',
            'content' => $text,
            'response' => $response,
        ]);

        return $response;
    }

    public function sendDocument($to, $documentUrl, $caption = null)
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'document',
            'document' => [
                'link' => $documentUrl,
                'caption' => $caption,
            ],
        ];

        $response = Http::withHeaders($this->headers())
            ->post("https://graph.facebook.com/v21.0/{$this->company->whatsapp_phone_number_id}/messages", $payload)
            ->json();

        Message::create([
            'company_id' => $this->company->id,
            'to' => $to,
            'direction' => 'outgoing',
            'type' => 'document',
            'content' => $caption,
            'response' => $response,
        ]);

        return $response;
    }
}
