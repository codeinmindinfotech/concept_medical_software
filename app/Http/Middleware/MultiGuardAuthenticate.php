<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;

class MultiGuardAuthenticate
{
    protected $guards = ['doctor', 'patient', 'clinic', 'web'];


public function handle(Request $request, Closure $next)
{
    foreach ($this->guards as $guard) {
        if (Auth::guard($guard)->check()) {
            Log::info("Authenticated via guard: $guard");
            Auth::shouldUse($guard);
            $response = $next($request);

            // Set CORS headers on response
            $response->headers->set('Access-Control-Allow-Origin', 'http://127.0.0.1:8000');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-CSRF-TOKEN, Authorization, X-Requested-With');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');

            return $response;
        }
    }

    Log::warning('MultiGuardAuthenticate failed: no guards matched.');
    abort(403, 'Unauthenticated via any guard.');
}

    // public function handle(Request $request, Closure $next)
    // {
    //     // Handle OPTIONS request early with CORS headers (preflight)
    //     if ($request->getMethod() === "OPTIONS") {
    //         return response('', 200)
    //             ->header('Access-Control-Allow-Origin', 'http://127.0.0.1:8000')
    //             ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
    //             ->header('Access-Control-Allow-Headers', 'Content-Type, X-CSRF-TOKEN, Authorization, X-Requested-With')
    //             ->header('Access-Control-Allow-Credentials', 'true');
    //     }

    //     // Set CORS headers on every response
    //     $response = $next($request);
    //     $response->headers->set('Access-Control-Allow-Origin', 'http://127.0.0.1:8000');
    //     $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    //     $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-CSRF-TOKEN, Authorization, X-Requested-With');
    //     $response->headers->set('Access-Control-Allow-Credentials', 'true');

    //     // Your existing multi-guard authentication check
    //     foreach ($this->guards as $guard) {
    //         if (Auth::guard($guard)->check()) {
    //             Auth::shouldUse($guard); // Mark this guard as current
    //             return $response;
    //         }
    //     }

    //     abort(403, 'Unauthenticated via any guard.');
    // }
}

