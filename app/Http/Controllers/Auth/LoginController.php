<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Company;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login'); // Blade with company + email + password
    }

    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'company' => 'required|string',
            'email'     => 'required|email',
            'password'     => 'required|string',
        ]);

        try {
            $company = Company::where('name', $credentials['company'])->first();

            if (!$company) {
                return back()->withErrors(['company_name' => 'Company not found']);
            }

            $guards = [
                'web' => User::class,
                'doctor' => Doctor::class,
                'patient' => Patient::class,
                'clinic' => Clinic::class,
            ];
        
            foreach ($guards as $guard => $modelClass) {
                $user = $modelClass::where('email', $credentials['email'])
                    ->where('company_id', $company->id)
                    ->first();
        
                if ($user && \Hash::check($credentials['password'], $user->password)) {
                    Auth::guard($guard)->login($user);
                    session(['login_guard' => $guard]); // Optional, for logout
                    return redirect($this->redirectTo());
                }
            }
            return back()->withErrors(['email' => 'Invalid credentials for this company.']);
        } catch (\Exception $e) {
            return back()->withErrors(['company' => $e->getMessage()]);
        }
    }

    protected function redirectTo()
    {
        $guards = [
            'web' => 'web',
            'clinic' => 'clinic',
            'doctor' => 'doctor',
            'patient' => 'patient',
        ];
        
        foreach ($guards as $guard => $routePrefix) {
            if (auth()->guard($guard)->check()) {
                if ($guard === 'web') {
                    $user = auth()->guard('web')->user();
                    if ($user?->hasRole('manager')) {
                        return '/manager/dashboard';
                    } elseif ($user?->hasRole('superadmin')) {
                        return '/dashboard';
                    }
                }
        
                return "/$routePrefix/dashboard";
            }
        }
    }

    public function logout(Request $request)
    {
        $guard = session('login_guard', 'web');
        Auth::guard($guard)->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
