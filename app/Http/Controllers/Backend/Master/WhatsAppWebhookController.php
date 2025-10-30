<?php

namespace App\Http\Controllers\Backend\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Message;

class WhatsAppWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();

        if (!empty($data['entry'][0]['changes'][0]['value']['messages'][0])) {
            $message = $data['entry'][0]['changes'][0]['value']['messages'][0];
            $phoneNumberId = $data['entry'][0]['changes'][0]['value']['metadata']['phone_number_id'];

            $company = Company::where('whatsapp_phone_number_id', $phoneNumberId)->first();

            if ($company) {
                Message::create([
                    'company_id' => $company->id,
                    'to' => $message['from'],
                    'direction' => 'incoming',
                    'type' => $message['type'] ?? 'text',
                    'content' => $message['text']['body'] ?? null,
                    'response' => $data,
                ]);
            }
        }

        return response()->json(['status' => 'received']);
    }

    // Webhook verification (Meta will call this once)
    public function verify(Request $request)
    {
        $verify_token = 'concept_medical_verify_2025'; // set your token
        if ($request->hub_verify_token === $verify_token) {
            return response($request->hub_challenge, 200);
        }
        return response('Error: Invalid token', 403);
    }
}

