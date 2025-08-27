<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle($request, Closure $next, $role): Response
    {
        $guards = ['superadmin', 'clinic', 'doctor', 'patient'];

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if (Auth::guard($guard)->user()->role === $role) {
                    return $next($request);
                }
            }
        }
        abort(403, 'Unauthorized.');
    }
}
