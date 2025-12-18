<?php

namespace App\Http\Controllers\Backend;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Clinic;
use App\Models\User;
use App\Notifications\DoctorMessageNotification;
use Illuminate\Support\Facades\Mail;

class DoctorMessageController extends Controller
{
    public function showForm()
    {
        $user = current_user();
        $patients = Patient::where('company_id', $user->company_id)
            ->where(function ($query) use ($user) {
                $query->where('doctor_id', $user->id)
                      ->orWhere('referral_doctor_id', $user->id)
                      ->orWhere('other_doctor_id', $user->id)
                      ->orWhere('solicitor_doctor_id', $user->id);
            })
            ->get();

        $managers = User::where('company_id', $user->company_id )->get();
        $clinics = Clinic::where('company_id', $user->company_id)->get();

        return view(guard_view('doctors.notifications.send', 'patient_admin.profile.doctor-send'), compact('patients', 'clinics', 'managers'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'recipients' => 'required|array',
            'recipients.*' => 'string',
        ]);

        $doctor = auth('doctor')->user();
        $message = $request->message;
        $notification = new DoctorMessageNotification($message, $doctor);

        foreach ($request->recipients as $recipient) {
            [$type, $id] = explode('-', $recipient);
            // $modelClass = $type === 'patient' ? \App\Models\Patient::class : \App\Models\Clinic::class;
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

