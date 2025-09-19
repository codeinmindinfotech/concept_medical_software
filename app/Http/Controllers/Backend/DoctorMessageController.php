<?php

namespace App\Http\Controllers\Backend;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Clinic;

use App\Notifications\DoctorMessageNotification;
use Pusher\Pusher;

class DoctorMessageController extends Controller
{
    public function showForm()
    {
        // $options = array(
        //     'cluster' => 'us3',
        //     'useTLS' => true
        //   );
        //   $pusher = new Pusher(
        //     '31acd5cb2f1e0b8edb56',
        //     '8575d690d2029f07b51d',
        //     '2052310',
        //     $options
        //   );
        
        //   $data['message'] = 'hello world niru';
        //   $pusher->trigger('my-channel', 'my-event', $data);

        $patients = Patient::where('company_id', auth('doctor')->user()->company_id)->get();
        $clinics = Clinic::where('company_id', auth('doctor')->user()->company_id)->get();

        return view('doctors.notifications.send', compact('patients', 'clinics'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'recipients' => 'required|array',
            'recipients.*' => 'string', // e.g. patient-1 or clinic-3
        ]);

        $doctor = auth('doctor')->user();
        $notification = new DoctorMessageNotification($request->message, $doctor);

        foreach ($request->recipients as $recipient) {
            [$type, $id] = explode('-', $recipient);
            $modelClass = $type === 'patient' ? \App\Models\Patient::class : \App\Models\Clinic::class;

            if ($recipientModel = $modelClass::find($id)) {
                $recipientModel->notify($notification);

                $latestNotification = $recipientModel->notifications()->latest()->first();
                // event(new MessageSent($latestNotification, $patient->id, $patient));

                event(new MessageSent($latestNotification, $recipientModel->id, $recipientModel));

            }
        }

        return redirect()->back()->with('success', 'Message sent successfully!');
    }
}

