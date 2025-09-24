<?php

namespace App\Http\Controllers\Backend;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;

use App\Notifications\ClinicMessageNotification;
use Illuminate\Support\Facades\Mail;

class ClinicMessageController extends Controller
{
    public function showForm()
    {
        $clinic = auth('clinic')->user();

        // Get all appointments for this clinic
        $appointments = $clinic->appointments()->with('patient')->get();

        // Extract patients from appointments (unique)
        $patients = $appointments->pluck('patient')->unique('id')->values();

        // Optional: Get all doctors under same company
        $doctors = Doctor::where('company_id', $clinic->company_id)->get();

        return view('clinics.notifications.send', compact('patients', 'doctors'));
    }
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'recipients' => 'required|array',
            'recipients.*' => 'string',
        ]);

        $clinic = auth('clinic')->user();
        $message = $request->message;
        $notification = new ClinicMessageNotification($message, $clinic);

        foreach ($request->recipients as $recipient) {
            [$type, $id] = explode('-', $recipient);
            $modelClass = $type === 'patient' ? \App\Models\Patient::class : \App\Models\Doctor::class;

            if ($recipientModel = $modelClass::find($id)) {
                $recipientModel->notify($notification);

                try {
                    Mail::to($recipientModel->email)->queue(new \App\Mail\NotificationMail($message));
                } catch (\Exception $e) {
                    \Log::error("Email queue failed for {$recipientModel->email}: " . $e->getMessage());
                }

                $latestNotification = $recipientModel->notifications()->latest()->first();
                event(new MessageSent($latestNotification, $recipientModel->id, $recipientModel));

            }
        }

        return redirect()->back()->with('success', 'Message sent successfully!');
    }
}

