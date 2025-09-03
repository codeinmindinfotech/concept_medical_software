<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        // $clinics = Clinic::all(); // Or use `->pluck('name', 'id')` for dropdowns
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Logout from all guards
        foreach (['superadmin', 'clinic', 'doctor', 'patient'] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }
        session()->flush();

        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'companyName' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $companyName = $request->companyName;

        \Log::info("Login attempt", [
            'companyName' => $companyName,
            'email' => $request->email,
        ]);

        // Step 1: Lookup company from main DB
        $company = \App\Models\Company::where('name', $companyName)->first();

        if (!$company) {
            \Log::error("Company not found: $companyName");
            return back()->withErrors(['companyName' => 'Company not found'])->withInput();
        }

        // Step 2: Switch to company DB
        try {
            switchToCompanyDatabase($company);
        } catch (\Throwable $e) {
            \Log::error("DB Switch failed for $companyName: " . $e->getMessage());
            return back()->withErrors(['companyName' => 'Could not connect to company database.'])->withInput();
        }

        // Step 3: Attempt logins in order of priority
        $guardAttempts = [
            'superadmin' => '/manager/dashboard',
            'clinic'     => '/clinic/dashboard',
            'doctor'     => '/doctor/dashboard',
            'patient'    => '/patient/dashboard',
        ];

        foreach ($guardAttempts as $guard => $redirectPath) {
            if (Auth::guard($guard)->attempt($credentials, $request->filled('remember'))) {
                $user = Auth::guard($guard)->user();

                session([
                    'user_id'       => $user->id,
                    'user_name'     => $user->name ?? ($user->first_name . ' ' . $user->last_name),
                    'company_name'  => $company->name,
                    'user_email'    => $user->email,
                    'user_type'     => $guard,
                    'auth_guard'    => $guard,
                ]);

                \Log::info(ucfirst($guard) . ' login successful');
                return redirect()->intended($redirectPath);
            }
        }

        // Final fallback
        \Log::warning('Login failed for all guards');
        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function logout(Request $request)
    {
        $guard = session('auth_guard', 'web'); // fallback to web or default guard if not found

        Auth::guard($guard)->logout();

        $request->session()->forget([
            'user_id',
            'user_name',
            'company_name',
            'user_email',
            'user_type',
            'auth_guard',
        ]);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

}
