<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Clinic;
use App\Helpers\DatabaseSwitcher;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home'; // Change as needed

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth:superadmin,clinic,doctor,patient')->only('logout');
    }

    public function showLoginForm()
    {
        $clinics = Clinic::all(); // Or use `->pluck('name', 'id')` for dropdowns
        return view('auth.login', compact('clinics'));
    }

    public function login(Request $request)
    {
        
        // Logout all guards before logging in a new user
        foreach (['superadmin', 'clinic', 'doctor', 'patient'] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
                session()->forget([
                    'clinic_id',
                    'clinic_name',
                    'clinic_code',
                    'user_email',
                    'user_type',
                    'auth_guard',
                ]);
            }
        }

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'companyName' => 'required|string',
            ]);

        $companyName = $request->companyName;
        $credentials = $request->only('email', 'password');
        \Log::info("Login attempt", [
            'companyName' => $companyName,
            'email' => $request->email,
        ]);

        // SUPERADMIN LOGIN
        if ($type === 'superadmin') {
            if (Auth::guard('superadmin')->attempt($credentials, $request->filled('remember'))) {
                \Log::info('Superadmin login successful');
                return redirect()->intended("/dashboard");
            } else {
                \Log::warning('Superadmin login failed');
                return back()->withErrors(['email' => 'Invalid superadmin credentials'])->withInput();
            }
        }
       
        // DOCTOR or PATIENT LOGIN (Tenant DB)
        $clinic = Clinic::where('id', $request->clinic_id)->first();

        if (!$clinic) {
            \Log::error('Clinic not found: ' . $request->clinic_id);
            return back()->withErrors(['clinic_name' => 'Clinic not found'])->withInput();
        }

        \Log::info('Switching to clinic DB: ' . $clinic->db_name);
        switchToCompanyDatabase($clinic);

        // Determine which table to check
        $table = match ($type) {
            'clinic' => 'clinics',
            'doctor' => 'doctors',
            'patient' => 'patients',
        };

        $user = DB::table($table)->where('email', $request->email)->first();
        $db_clinic = DB::table('clinics')->where('code', $clinic->code)->first();

        // CLINIC LOGIN
        if ($type === 'clinic') {
            \Log::info('Attempting clinic login');
  
            if (Auth::guard('clinic')->attempt($credentials, $request->filled('remember'))) {
                session([
                    'clinic_id' => $db_clinic->id,
                    'clinic_name' => $db_clinic->name,
                    'clinic_code' => $db_clinic->code,
                    'user_email' => $db_clinic->email,
                    'user_type' => $type,
                    'auth_guard' => $type
                ]);
                \Log::info('Clinic login successful');
                \Log::info('Clinic ID from session:', ['clinic_id' => session('clinic_code')]);

                return redirect()->intended("/{$type}/dashboard");
            } else {
                \Log::warning('Clinic login failed');
                return back()->withErrors(['email' => 'Invalid clinic credentials'])->withInput();
            }
        }

        if (in_array($type, ['doctor', 'patient'])) {
            \Log::info("Attempting {$type} login");
        
            if (Auth::guard('doctor')->attempt($credentials, $request->filled('remember'))) {
                session([
                    'clinic_id' => $db_clinic->id,
                    'clinic_name' => $db_clinic->name,
                    'clinic_code' => $db_clinic->code,
                    'user_email' => $user->email,
                    'user_type' => $type,
                    'auth_guard' => $type
                ]);
        
                \Log::info(ucfirst($type) . ' login successful');
        
                return redirect()->intended("/{$type}/dashboard");
            }
        
            \Log::warning("{$type} login failed");
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }
        

        \Log::warning("{$type} login failed");
        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }


    public function logout(Request $request)
    {
        $guard = session('auth_guard', 'web'); // fallback to web or default guard if not found

        Auth::guard($guard)->logout();

        $request->session()->forget([
            'clinic_id',
            'clinic_name',
            'clinic_code',
            'user_email',
            'user_type',
            'auth_guard',
        ]);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

}
