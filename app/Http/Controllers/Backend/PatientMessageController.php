<?php

namespace App\Http\Controllers\Backend;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;

use App\Notifications\PatientMessageNotification;
use Illuminate\Support\Facades\Mail;

class PatientMessageController extends Controller
{
    public function showForm()
    {
        $patient = auth('patient')->user();

        // Get all appointments for this patient
        $appointments = Appointment::with('patient')->where('patient_id',$patient->id)->get();

        // Extract patients from appointments (unique)
        $clinics = $appointments->pluck('clinic')->unique('id')->values();

        // Optional: Get all doctors under same company
        $doctors = Doctor::where('company_id', $patient->company_id)->get();

        return view('patients.notifications.send', compact('clinics', 'doctors'));
    }
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'recipients' => 'required|array',
            'recipients.*' => 'string',
        ]);

        $clinic = auth('patient')->user();
        $message = $request->message;
        $notification = new PatientMessageNotification($message, $clinic);

        foreach ($request->recipients as $recipient) {
            [$type, $id] = explode('-', $recipient);
            $modelClass = $type === 'clinic' ? \App\Models\Clinic::class : \App\Models\Doctor::class;

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

