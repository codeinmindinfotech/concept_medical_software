<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

// class CheckRole
// {
//     public function handle($request, Closure $next, $role): Response
//     {
//         $guards = ['superadmin', 'clinic', 'doctor', 'patient','manager'];

//         foreach ($guards as $guard) {
//             if (Auth::guard($guard)->check()) {
//                 if (Auth::guard($guard)->user()->role === $role) {
//                     return $next($request);
//                 }
//             }
//         }
//         abort(403, 'Unauthorized.');
//     }
// }
class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        $user = auth()->user();

        if (! $user || $user->role !== $role) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
