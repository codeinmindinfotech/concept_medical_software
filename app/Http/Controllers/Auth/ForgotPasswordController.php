<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'company' => 'required|string',
            'email' => 'required|email',
        ]);

        $company = Company::where('name', $request->company)->first();

        if (!$company) {
            return back()->withErrors(['company' => 'Company not found']);
        }

        $guards = [
            'web' => \App\Models\User::class,
            'doctor' => \App\Models\Doctor::class,
            'patient' => \App\Models\Patient::class,
            'clinic' => \App\Models\Clinic::class,
        ];

        foreach ($guards as $guard => $model) {
            $user = $model::where('email', $request->email)
                        ->where('company_id', $company->id)
                        ->first();

            if ($user) {
                $status = Password::sendResetLink(['email' => $request->email]);

                return $status === Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withErrors(['email' => __($status)]);
            }
        }

        return back()->withErrors(['email' => 'No user found with this email under this company.']);
    }


}
