<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\AuthHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordChangeController extends Controller
{

    public function showForm()
    {
        return view('auth.change-password');
    }

    public function update(Request $request) : JsonResponse
    {
        $guard = getCurrentGuard();

        if (!$guard || !auth($guard)->check()) {
            return response()->json([
                'success' => false,
                'errors' => 'User not authenticated.'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8',
        ], [
            'new_password.required' => 'Please enter a new password.',
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password.confirmed' => 'Password confirmation does not match.',
            'new_password_confirmation.required' => 'Please confirm your new password.',
        ]);
        

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth($guard)->user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'redirect' => guard_route('password.change'),
            'message' => 'Password changed successfully!',
        ]);
    }
}
