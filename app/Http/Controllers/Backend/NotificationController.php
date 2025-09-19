<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Clinic;
use App\Notifications\GeneralNotification;
use Illuminate\Notifications\DatabaseNotification;


class NotificationController extends Controller
{
    public function showForm()
    {
        return view('notifications.send'); // Path: resources/views/notifications/send.blade.php
    }


    public function sendToCompany(Request $request)
    {
        $user = auth('web')->user(); // SuperAdmin or Manager

        $request->validate([
            'message' => 'required|string|max:1000',
            'company_id' => $user->role === 'SuperAdmin' ? 'required|exists:companies,id' : '',
        ]);

        $companyId = has_role('superadmin')
            ? $request->input('company_id')
            : $user->company_id;

        $message = $request->input('message');

        $notification = new GeneralNotification($message, $user->name, $companyId);

        // Notify users in the company
        Doctor::where('company_id', $companyId)->each(fn($d) => $d->notify($notification));
        Patient::where('company_id', $companyId)->each(fn($p) => $p->notify($notification));
        Clinic::where('company_id', $companyId)->each(fn($c) => $c->notify($notification));

        return redirect()->back()->with('success', 'Notification sent successfully!');
    }

    public function markAllAsRead(Request $request)
    {
        $guards = ['doctor', 'patient', 'clinic', 'web'];

        foreach ($guards as $guard) {
            if (auth($guard)->check()) {
                auth($guard)->user()->unreadNotifications->markAsRead();
                return response()->json(['status' => 'marked as read']);
            }
        }

        return response()->json(['status' => 'unauthenticated'], 401);
    }

}


