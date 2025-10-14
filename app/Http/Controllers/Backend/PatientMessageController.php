<?php

namespace App\Http\Controllers\Backend;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\User;
use App\Notifications\PatientMessageNotification;
use Illuminate\Support\Facades\Mail;

class PatientMessageController extends Controller
{
    public function showForm()
    {
        $patient = auth('patient')->user();

        $doctorIds = collect([
            $patient->doctor_id,
            $patient->referral_doctor_id,
            $patient->other_doctor_id,
            $patient->solicitor_doctor_id,
        ])->filter()->unique()->values(); // Remove nulls and duplicates

        $doctors = Doctor::whereIn('id', $doctorIds)->get();

        $managers = User::where('company_id', $patient->company_id )->get();

        return view('patients.notifications.send', compact( 'doctors','managers'));
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
            $modelClass = $type === 'manager' ? \App\Models\User::class : \App\Models\Doctor::class;

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

