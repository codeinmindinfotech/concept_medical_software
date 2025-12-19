<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WebexInteractSms
{
    protected $baseUrl = 'https://api.webexinteract.com/v1/';

    public function sendSms(array $data, $company)
    {
        if (!isset($company->webex_token) || !isset($company->webex_sender)) {
            throw new \Exception("Company SMS credentials not set.");
        }

        $payload = [
            'message_body' => $data['message_body'] ?? '',
            'from' => $company->webex_sender,
            'to' => [
                ['phone' => $data['to']]  // array of numbers
            ],
        ];

        $response = Http::withToken($company->webex_token)
            ->post($this->baseUrl . 'sms', $payload);

        return $response->json();
    }
}