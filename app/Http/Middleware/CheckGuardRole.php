<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckGuardRole
{
    public function handle($request, Closure $next)
    {
        $guard = request()->segment(1); // Assuming the route prefix is the guard (e.g., /doctor/dashboard)
        if (!in_array($guard, ['doctor', 'clinic', 'patient', 'web'])) {
            abort(403, 'Unauthorized guard.');
        }

        if (!Auth::guard($guard)->check()) {
            return redirect()->route("$guard.login");
        }

        return $next($request);
    }
}

