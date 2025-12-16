<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckGuardRole
{
    public function handle($request, Closure $next)
    {
        $guard = $request->segment(1); // e.g., /doctor/dashboard
        if (!in_array($guard, ['doctor', 'clinic', 'patient', 'web'])) {
            abort(403, 'Unauthorized guard.');
        }

        if (!Auth::guard($guard)->check()) {
            return redirect()->route("$guard.login");
        }

        $user = Auth::guard($guard)->user();

        // Superadmin bypass
        if ($user->hasRole('superadmin')) {
            return $next($request);
        }

        // Company restriction (assuming company_id column exists in users)
        if ($request->route()->hasParameter('company_id')) {
            $companyId = $request->route('company_id');
            if ($companyId != $user->company_id) {
                abort(403, 'Unauthorized company access.');
            }
        }

        return $next($request);
    }
}


