<?php

namespace App\Http\Middleware;

use App\Models\Clinic;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SwitchClinicDatabase
{
    public function handle($request, Closure $next, $guard = null)
    {
        //  // Get the authenticated user using the provided guard or default guard
        //  $user = $guard ? Auth::guard($guard)->user() : Auth::user();

        //  // Log the user and guard info for debugging
        //  \Log::info('Middleware user check:', [
        //      'guard' => $guard ?? 'default',
        //      'user' => $user ? $user->toArray() : null,
        //  ]);

         $clinicCode = session('clinic_code');

        if ($clinicCode) {
            $clinic = Clinic::where('code', $clinicCode)->first(); // use code instead of ID
            if ($clinic) {
                switchToClinicDatabase($clinic);
            }
        }

        // Log the user and guard info for debugging
        \Log::info('Middleware user check:', [
            'guard' => $guard ?? 'default',
            'user' => $clinic ? $clinic->toArray() : null,
        ]);
        return $next($request);
    }
}
