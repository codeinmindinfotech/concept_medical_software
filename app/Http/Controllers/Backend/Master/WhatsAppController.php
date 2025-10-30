<?php

namespace App\Http\Controllers\Backend\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Message;
use App\Services\WhatsAppService;

class WhatsAppController extends Controller
{
    public function sendText(Request $request, $companyId)
    {
        $request->validate([
            'to' => 'required|string',
            'message' => 'required|string',
        ]);

        $company = Company::findOrFail($companyId);
        $service = new WhatsAppService($company);

        $response = $service->sendTextMessage($request->to, $request->message);
        return response()->json($response);
    }

    public function sendDocument(Request $request, $companyId)
    {
        $request->validate([
            'to' => 'required|string',
            'document_url' => 'required|url',
            'caption' => 'nullable|string',
        ]);

        $company = Company::findOrFail($companyId);
        $service = new WhatsAppService($company);

        $response = $service->sendDocument($request->to, $request->document_url, $request->caption);
        return response()->json($response);
    }

    public function sendRuntime(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
            'appointment_id' => 'nullable|integer'
        ]);

        // get current company (adjust if company is tied to user/session)
        $company = auth()->user()->company; // or auth()->user()->company etc.
        // If no company (superadmin), fallback to .env credentials
        if (!$company) {
            $company = new \App\Models\Company([
                'id' => 0, // virtual ID, not in DB
                'name' => 'SuperAdmin',
                'whatsapp_phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
                'whatsapp_business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),
                'whatsapp_access_token' => env('WHATSAPP_ACCESS_TOKEN'),
            ]);
        }
        if (!$company || !$company->whatsapp_access_token) {
            return response()->json(['error' => 'Company WhatsApp credentials missing'], 422);
        }

        // Send message via your WhatsAppService
        $service = new \App\Services\WhatsAppService($company);
        $response = $service->sendTextMessage($request->phone, $request->message);

        Message::create([
            'company_id' => $company->id,
            'appointment_id' => $request->appointment_id,
            'to' => $request->phone,
            'direction' => 'outgoing',
            'type' => 'text',
            'content' => $request->message,
            'response' => $response,
        ]);

        return response()->json(['success' => true, 'response' => $response]);
    }

}

