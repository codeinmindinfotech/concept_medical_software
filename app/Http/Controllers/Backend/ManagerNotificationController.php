<?php

namespace App\Http\Controllers\Backend;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Notifications\ManagerMessageNotification;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Mail;
use PhpParser\Comment\Doc;

class ManagerNotificationController extends Controller
{
    public function showManagerForm()
    {
        $user = current_user();
        $users = User::role('superadmin')->get();
        $patients = Patient::where('company_id', $user->company_id)->get();
        $clinics = Clinic::where('company_id', $user->company_id)->get();
        $doctors = Doctor::where('company_id', $user->company_id)->get();
        return view('notifications.managersend', compact('patients', 'clinics','doctors', 'users'));
    }

    public function sendFromManager(Request $request)
    {
        $user = auth('web')->user(); // SuperAdmin or Manager

        $request->validate([
            'message' => 'required|string|max:1000',
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'string'
        ]);

        $companyId = $user->company_id;

        $message = $request->input('message');

        foreach ($request->recipients as $recipient) {
            [$type, $id] = explode('-', $recipient);

            switch ($type) {
                case 'user':
                    $model = \App\Models\User::find($id);
                    break;
                case 'patient':
                    $model = \App\Models\Patient::find($id);
                    break;
                case 'doctor':
                    $model = \App\Models\Doctor::find($id);
                    break;
                case 'clinic':
                    $model = \App\Models\Clinic::find($id);
                    break;
                default:
                    $model = null;
            }

            \Log::info("Sending to recipient: {$type}-{$id} => " . ($model ? 'FOUND' : 'NOT FOUND'));

            if ($model) {
                \Log::info("Sending notification to: {$model->id}");

                $model->notify(new \App\Notifications\ManagerMessageNotification($message, $user, $companyId));

                try {
                    if (!empty($model->email)) {
                        Mail::to($model->email)->queue(new \App\Mail\NotificationMail($message));
                    }
                } catch (\Exception $e) {
                    \Log::error("Failed to send email to {$model->email}: " . $e->getMessage());
                }

                $latestNotification = $model->notifications()->latest()->first();
                if ($latestNotification) {
                    event(new \App\Events\MessageSent($latestNotification, $model->id, $model));
                }
            }
        }

        return redirect()->back()->with('success', 'Notification sent successfully!');
    }

}


