<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    // use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';
    
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.custom_reset')->with([
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'type' => 'nullable|string',
            'company_id' => 'nullable|integer', 
        ]);

        $type = $request->input('type');
        $email = $request->input('email');
        $companyId = $request->input('company_id');

        $type = $type ?: 'user';

        
        // Dynamically determine which model to use based on 'type'
        $modelClass = match ($type) {
            'clinic' => Clinic::class,
            'doctor' => Doctor::class,
            'patient'  => Patient::class,
            'user'  => User::class,
        };

        if (!$modelClass) {
            return back()->withErrors(['email' => 'Invalid user type.']);
        }
        \Log::info('Reset Attempt', [
            'model' => $modelClass,
            'email' => $email,
            'company_id' => $companyId,
            'type' => $type
        ]);
        $query = $modelClass::where('email', $email);
        
        $userCandidate = $modelClass::where('email', $email)->first();

        if (
            (in_array($type, ['clinic', 'doctor', 'patient']) && $companyId) ||
            ($type === 'user' && $companyId && $userCandidate && $userCandidate->hasRole('manager'))
        ) {
            $query->where('company_id', $companyId);
        }

        $user = $query->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'We couldn’t find a user matching these credentials.']);
        }

        // Reset the password using the broker (e.g., clinics, doctors, etc.)
        $brokerManager = app('auth.password'); // CustomPasswordBrokerManager
        $brokerManager->setCompanyId($request->input('company_id'));
        $brokerManager->setType($request->input('type') ?? 'user'); // fallback if null
        
        $broker = $brokerManager->broker($request->input('type') . 's'); // example: 'clinics'
        
        $status = $broker->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
        
                event(new PasswordReset($user));
            }
        );
        dd($status);
        if ($status == Password::PASSWORD_RESET) {
            if ($type === 'user' && $user->hasRole('superadmin')) {
                return redirect()->to('/superadmin/login')->with('status', __($status));
            }

            return redirect()->route('login')->with('status', __($status));
        }

        return back()->withErrors(['email' => __($status)]);
    }

}
