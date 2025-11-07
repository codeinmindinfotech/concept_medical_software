<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;

class BroadcastController extends Controller
{
    public function authenticate(Request $request)
    {
        // \Log::info('Pusher auth called', [
        //     'user' => auth()->user(),
        //     'guard' => auth()->getDefaultDriver(),
        //     'request' => $request->all(),
        // ]);

        // Ensure the guard used is the same as the one in your middleware
        $guard = auth()->getDefaultDriver();
        // \Log::info('current auth guard', [
        //     'guard' => auth()->getDefaultDriver(),
        // ]);
        if (!auth()->check()) {
            \Log::warning('Unauthorized');
            abort(403, 'Unauthorized');
        }

        // \Log::info('current auth guard end', [
        //     'guard' => auth()->getDefaultDriver(),
        // ]);

        // If needed, explicitly set guard before calling Broadcast::auth()
        auth()->shouldUse($guard);

        // \Log::info('shouldUse', [
        //     'shouldUse' => auth()->shouldUse($guard),
        // ]);

        if ($request->hasSession()) {
            // \Log::info('hasSession', [
            //     'hasSession' => $request->hasSession(),
            // ]);
    
            $request->session()->reflash();
        }
        
        return Broadcast::auth($request);
    }

    // public function authenticate(Request $request)
    // {
    //     $guard = getCurrentGuard();  // Get guard dynamically

    //     \Log::info('Pusher auth called', [
    //         'user' => current_user(),
    //         'guard' => Auth::guard($guard),
    //         'request' => $request->all()
    //     ]);

    //     if (!Auth::guard($guard)->check()) {
    //         \Log::warning('Unauthorized');
    //         \Log::warning('Broadcast auth failed: no authenticated guard');
    //         abort(403, 'Unauthorized');
    //     }

    //     if ($request->hasSession()) {
    //         $request->session()->reflash();
    //     }

    //     // Authenticated, so no error log here

    //     return Broadcast::auth($request);
    // }


}

