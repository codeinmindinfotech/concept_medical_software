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
            ]);

            return redirect()->intended('/dashboard'); // or /admin/dashboard
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }
}