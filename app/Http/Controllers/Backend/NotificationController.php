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
use App\Notifications\GeneralNotification;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = current_user(); // Or use your current_user() helper

        $notifications = $user->notifications()->latest()->paginate(10);
        if ($request->ajax()) {
            return view('notifications.list', compact('notifications'))->render();
        }

        return view('notifications.index',compact('notifications'));

    }

    public function showForm()
    {
        return view('notifications.send'); // Path: resources/views/notifications/send.blade.php
    }

    public function sendToCompany(Request $request)
    {
        $user = auth('web')->user(); // SuperAdmin or Manager
        $data = $request->validate([
            'message' => 'required|string|max:1000',
            'user_ids' => $user->hasRole('superadmin') ?'required|array':'',
            'user_ids.*' => 'exists:users,id',
            'company_id' => $user->hasRole('superadmin') ? 'required|exists:companies,id' : '',
        ]);
        

        $companyId = has_role('superadmin')
            ? $request->input('company_id')
            : $user->company_id;

        $message = $request->input('message');

        $notification = new GeneralNotification($message, $user->name, $companyId);
        if($user->hasRole('superadmin'))
        {
            // Notify selected users only
            $recipientUsers = \App\Models\User::whereIn('id', $request->user_ids)->get();

            foreach ($recipientUsers as $recipient) {
                $recipient->notify($notification);

                try {
                    Mail::to($recipient->email)->queue(new \App\Mail\NotificationMail($message));
                } catch (\Exception $e) {
                    \Log::error("Email queue failed for {$recipient->email}: " . $e->getMessage());
                }

                $latestNotification = $recipient->notifications()->latest()->first();
                event(new MessageSent($latestNotification, $recipient->id, $recipient));
            }
        } else {
            foreach ([Doctor::class, Patient::class, Clinic::class, User::class] as $model) {
                $model::where('company_id', $companyId)->each(function ($recipient) use ($notification) {
                    $recipient->notify($notification);
            
                    $latestNotification = $recipient->notifications()->latest()->first();
                    event(new MessageSent($latestNotification, $recipient->id, $recipient));
                });
            }
        }    
           
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully!',
                'redirect' => route('notifications.form')
            ]);
        }

    // Normal browser request → redirect
    return redirect(guard_route('notifications.form'))
        ->with('success', 'Notification sent successfully!');
    }

    // public function markAsRead(Request $request)
    // {
    //     $user = current_user();

    //     $request->validate([
    //         'ids' => 'nullable|array',
    //         'ids.*' => 'string', 
    //     ]);

    //     if ($request->filled('ids')) {
    //         $user->notifications()
    //             ->whereIn('id', $request->ids)
    //             ->whereNull('read_at')
    //             ->get()
    //             ->each
    //             ->markAsRead();

    //         // $user->notifications()->whereIn('id', $request->ids)->update(['read_at' => now()]);
    //     } else {
    //         // Mark all unread notifications as read
    //         $user->unreadNotifications()->update(['read_at' => now()]);
    //     }

    //     return response()->json(['success' => true]);
    // }

    public function markAsRead(Request $request)
    {
        $user = current_user();

        $request->validate([
            'ids' => 'nullable|array',
            'ids.*' => 'string',
            'id' => 'nullable|string',
        ]);

        if ($request->filled('ids')) {
            $user->notifications()
                ->whereIn('id', $request->ids)
                ->whereNull('read_at')
                ->get()
                ->each
                ->markAsRead();
        } elseif ($request->filled('id')) {
            $notification = $user->notifications()->where('id', $request->id)->first();
            if ($notification && is_null($notification->read_at)) {
                $notification->markAsRead();
            }
        } else {
            $user->unreadNotifications()->update(['read_at' => now()]);
        }

        return response()->json(['success' => true]);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = current_user();
        $user->unreadNotifications->markAsRead();

        return response()->json([
            'redirect' =>guard_route('notifications.index'),
            'message' => 'Notifications mark as read successfully',
        ]);
    }

    public function unread()
    {
        $user = current_user(); // ✅ CORRECT
        return $user->unreadNotifications()->take(10)->get();
    }

    public function markSingleAsRead(Request $request)
    {
        $user = current_user();

        $request->validate([
            'id' => 'required|string|exists:notifications,id',
        ]);

        $notification = $user->notifications()->where('id', $request->id)->first();

        if ($notification && is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }


}


