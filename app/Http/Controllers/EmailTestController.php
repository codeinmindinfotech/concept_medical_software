<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;

class EmailTestController extends Controller
{
    public function showForm()
    {
        return view('email-test');
    }

    public function sendEmail(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        // Send email
        Mail::to($request->email)->send(new TestEmail($request->message));

        return back()->with('success', 'Email sent successfully to '.$request->email);
    }
}
