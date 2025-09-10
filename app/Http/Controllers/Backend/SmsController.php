<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Jobs\SendScheduledSms;
use App\Models\Communication;
use App\Models\Patient;
use App\Models\SmsDefaultMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function index(Patient $patient)
    {
        $templates = SmsDefaultMessage::all(); 
        return view('patients.dashboard.sms.index', compact('templates','patient'));
    }

    public function store(Request $request) : JsonResponse
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'content' => 'required|string',
        ]);

        if ($request->send_option === 'now') {
            // to do when sms service we have
            // $this->sendSmsNow($patientId, $request->content);

            Communication::create([
                'patient_id' => $request->patient_id,
                'message' => $request->content,
                'method' => 'sms',
                'received' => true,
            ]);

            return response()->json([
                'redirect' =>guard_route('sms.index', ['patient' => $request->patient_id]),
                'message' => 'SMS created successfully',
            ]);
        }

        $scheduledAt = now()->addMinutes(30);

        Communication::create([
            'patient_id' => $request->patient_id,
            'message' => $request->content,
            'method' => 'sms',
            'received' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        dispatch(new SendScheduledSms(
            $request->patient_id,
            $request->content,
            $scheduledAt
        ))->delay($scheduledAt);

        return response()->json([
            'redirect' =>guard_route('sms.index', ['patient' => $request->patient_id]),
            'message' => 'SMS created successfully',
        ]);
    }

}
