<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login'); // create this view (shown below)
    }

    public function login(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        
        // Use superadmin guard (main DB)
        if (Auth::guard('superadmin')->attempt($credentials, $request->filled('remember'))) {
            $user = Auth::guard('superadmin')->user();

            session([
                'user_id'     => $user->id,
                'user_name'   => $user->name,
                'user_email'  => $user->email,
                'user_type'   => 'superadmin',
                'auth_guard'  => 'superadmin',
                'is_company_user' => false
            ]);

            \Log::info(' superadmin login successful');
                \Log::info($user);
            return redirect()->intended('/dashboard'); // or /admin/dashboard
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function logout(Request $request)
    {
        $guard = session('auth_guard', 'superadmin'); // default to superadmin if not set

        Auth::guard($guard)->logout();

        $request->session()->forget([
            'user_id',
            'user_name',
            'user_email',
            'user_type',
            'auth_guard',
            'is_company_user'
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('admin/login'); // or wherever you want to redirect after logout
    }

}