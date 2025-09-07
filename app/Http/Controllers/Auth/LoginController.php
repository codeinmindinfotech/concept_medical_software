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
        $this->middleware('auth:superadmin,clinic,doctor,patient,manager')->only('logout');
    }

    public function showLoginForm()
    {
        // $clinics = Clinic::all(); // Or use `->pluck('name', 'id')` for dropdowns
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Logout from all guards
        foreach (['superadmin', 'clinic', 'doctor', 'patient','manager'] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }
        // Invalidate the current session and regenerate CSRF token
    $request->session()->invalidate();
    $request->session()->regenerateToken();

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
            echo "connected";
            switchToCompanyDatabase($company);
            session(['db_connection' => $company->database_name]);
            // dd(\DB::connection('tenant')->getDatabaseName());
            // dd(config('database.connections.tenant'));


        } catch (\Throwable $e) {
            echo "not connected";
            \Log::error("DB Switch failed for $companyName: " . $e->getMessage());
            return back()->withErrors(['companyName' => 'Could not connect to company database.'])->withInput();
        }

        // Step 3: Attempt logins in order of priority
        $guardAttempts = [
            'manager' => '/manager/dashboard1',
            'clinic'  => '/clinic/dashboard',
            'doctor'  => '/doctor/dashboard',
            'patient' => '/patient/dashboard',
        ];

        // dd([
        //     'db_used' => (new \App\Models\User)->getConnection()->getDatabaseName(),
        //     'connection' => (new \App\Models\User)->getConnectionName(),
        // ]);

        foreach ($guardAttempts as $guard => $redirectPath) {
            Auth::shouldUse($guard);
            if (Auth::guard($guard)->attempt($credentials, $request->filled('remember'))) {
                $user = Auth::guard($guard)->user();
                $connection = [
                    'driver' => 'mysql',
                    'host' => $company->db_host ?? '127.0.0.1',
                    'port' => $company->db_port ?? '3306',
                    'database' => $company->db_database,
                    'username' => $company->db_username,
                    'password' => $company->db_password,
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
                    'strict' => true,
                    'engine' => null,
                ];
            
                session([
                    'user_id'       => $user->id,
                    'user_name'     => $user->name ?? ($user->first_name . ' ' . $user->last_name),
                    'company_name'  => $company->name,
                    'user_email'    => $user->email,
                    'user_type'     => $guard,
                    'auth_guard'    => $guard,
                    'company_db_connection'=> 'tenant',
                    'tenant_db_config'=> $connection,
                    'is_company_user' => in_array($guard, ['manager', 'clinic', 'doctor', 'patient']),
                ]);

                \Log::info(ucfirst($guard) . ' login successful');
                \Log::info($redirectPath);
                \Log::info('Tenant DB Connection Config at login/middleware:', config('database.connections.tenant'));
                Auth::guard($guard)->loginUsingId($user->id);
                switchToCompanyDatabase($company);
                // return redirect()->intended('/debug-db');

                return redirect()->intended($redirectPath);
            }
        }
dd("12");
        // Final fallback
        \Log::warning('Login failed for all guards');
        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function logout(Request $request)
    {
        // fallback to web or default guard if not found
        foreach (['superadmin', 'manager', 'clinic', 'doctor', 'patient'] as $guard) {
            Auth::guard($guard)->logout();
        }
        session()->flush();
        

        $request->session()->forget([
            'user_id',
            'user_name',
            'company_name',
            'user_email',
            'user_type',
            'auth_guard',
            'is_company_user'
        ]);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

}
