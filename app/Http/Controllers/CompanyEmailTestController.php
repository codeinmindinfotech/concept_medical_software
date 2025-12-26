<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;

class CompanyEmailTestController extends Controller
{
    public function send(): JsonResponse
    {
        try {
            $user = auth()->user();

            Mail::raw(
                'This is a test email. Your email configuration is working correctly.',
                function ($message) use ($user) {
                    $message->to($user->email)
                            ->subject('Test Email - SMTP Configuration');
                }
            );

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to ' . $user->email,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}

