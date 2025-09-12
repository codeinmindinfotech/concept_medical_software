<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperadminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:web')->except('logout');
    }

    // Show login form
    public function showLoginForm()
    {
        return view('auth.superadmin-login');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();
        // Attempt login on default DB with web guard
        if (Auth::guard('web')->attempt(
            ['email' => $request->email, 'password' => $request->password],
            $request->filled('remember')
        )) {
            $user = Auth::guard('web')->user();

            if (!$user->hasRole('superadmin')) {
                Auth::guard('web')->logout(); 
                return back()->withErrors([
                    'email' => 'Access denied. You are not a super-admin.',
                ]);
            }
            return redirect()->intended('/dashboard'); 
        } 

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/superadmin/login');
    }
}