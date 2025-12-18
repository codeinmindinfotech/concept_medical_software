<?php

namespace App\Http\Controllers\Backend;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Clinic;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\User;
use App\Notifications\ClinicMessageNotification;
use Illuminate\Support\Facades\Mail;

class ClinicMessageController extends Controller
{
    public function showForm()
    {
        $clinic = auth('clinic')->user();

        // Get all appointments for this clinic
        $appointments = Appointment::with('clinic','patient')->get()->where('id',$clinic->id)->where('company_id', $clinic->company_id);

        // Extract patients from appointments (unique)
        $patients = $appointments->pluck('patient')->unique('id')->values();

        $managers = User::where('company_id', $clinic->company_id )->get();

        // Optional: Get all doctors under same company
        $doctors = Doctor::where('company_id', $clinic->company_id)->get();

        return view(guard_view('clinics.notifications.send', 'patient_admin.profile.clinic-send'), compact('patients', 'doctors','managers'));
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

            $modelClass = match ($type) {
                'manager' => \App\Models\User::class,
                'patient' => \App\Models\Patient::class,
                default   => \App\Models\Doctor::class,
            };

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

