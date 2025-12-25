<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;

class MultiGuardAuthenticate
{
    // protected $guards = ['doctor', 'patient', 'clinic', 'web'];

    protected $guards = ['patient', 'web'];

    public function handle(Request $request, Closure $next)
    {
        foreach ($this->guards as $guard) {
            Log::info('Guards check', [
                'web' => Auth::guard('web')->check(),
                'patient' => Auth::guard('patient')->check(),
            ]);
            
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
}

