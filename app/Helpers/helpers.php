<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

if (!function_exists('format_date')) {
    function format_date($date, $format = 'd M Y', $default = '-')
    {
        if (!$date) {
            return $default;
        }

        if ($date instanceof \DateTimeInterface) {
            return Carbon::instance($date)->format($format);
        }

        try {
            return Carbon::parse($date)->format($format);
        } catch (Exception $e) {
            return $default;
        }
    }
}

if (! function_exists('format_time')) {
    function format_time($time, $format = 'H:i', $default = '-')
    {
        if (!$time) return $default;

        try {
            $dt = $time instanceof \DateTimeInterface
                ? Carbon::instance($time)
                : Carbon::parse($time);

            return $dt->format($format);
        } catch (\Exception $e) {
            return $default;
        }
    }
}

if (!function_exists('asset_url')) {
    function asset_url($path) {
        if (app()->environment('local')) {
            return asset('storage/' . $path);
        } else {
            return asset('storage/' . $path);
        }
    }
}

if (!function_exists('assignRoleToGuardedModel')) {
    function assignRoleToGuardedModel($model, string $roleName, string $guardName): void
    {
        $role = \Spatie\Permission\Models\Role::where('name', $roleName)
            ->where('guard_name', $guardName)
            ->first();

        if (!$role) {
            throw new \Exception("Role '{$roleName}' with guard '{$guardName}' not found.");
        }

        // Assign role to the model
        $model->assignRole($role);
    }
}

if (! function_exists('getAuthenticatedUserAndCompany')) {
    function getAuthenticatedUserAndCompany(): array
    {
        $guards = array_keys(config('auth.guards'));
        
        foreach ($guards as $guard) {
            if (auth()->guard($guard)->check()) {
                $user = auth()->guard($guard)->user();
                $companyId = $user->company_id ?? null; // Assuming your User model has company_id
                
                return [
                    'user' => $user,
                    'guard' => $guard,
                    'company_id' => $companyId,
                ];
            }
        }
        
        return [
            'user' => null,
            'guard' => null,
            'company_id' => null,
        ];
    }
}

if (!function_exists('getCurrentGuard')) {
    function getCurrentGuard(): ?string
    {
        $guards = ['web', 'clinic', 'doctor', 'patient'];

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $guard;
            }
        }

        return null;
    }
}

if (!function_exists('current_user')) {
    function current_user()
    {
        $guard = getCurrentGuard();
        return $guard ? Auth::guard($guard)->user() : null;
    }
}

if (!function_exists('has_role')) {
    function has_role($role): bool
    {
        $user = current_user();
        return $user && $user->hasRole($role);
    }
}

if (!function_exists('has_permission')) {
    function has_permission($permission): bool
    {
        $user = current_user();
        return $user && $user->can($permission);
    }
}

if (!function_exists('guard_route')) {
    function guard_route(string $baseRoute, $params = []): string
    {
        $guard = getCurrentGuard();
        $user = current_user();

        // Special logic for web guard
        if ($guard === 'web') {
            if ($user && $user->hasRole('superadmin')) {
                return route($baseRoute, $params); // e.g. 'dashboard'
            }

            if ($user && $user->hasRole('manager')) {
                $prefixedRoute = 'manager' . '.' . $baseRoute; // e.g. clinic.dashboard

                if (Route::has($prefixedRoute)) {
                    return route($prefixedRoute, $params);
                }
            }
        }

        // For other guards, use guard-prefixed route
        if ($guard) {
            $prefixedRoute = $guard . '.' . $baseRoute; // e.g. clinic.dashboard

            if (Route::has($prefixedRoute)) {
                return route($prefixedRoute, $params);
            }
        }

        // Default fallback
        return route($baseRoute, $params);
    }
}

if (!function_exists('user_name')) {
    function user_name(): string
    {
        $user = current_user(); 
        return $user?->name ?? $user?->full_name;
    }
}

if (!function_exists('current_company_id')) {
    function current_company_id(): ?int
    {
        $user = current_user();
        $guard = getCurrentGuard();

        if (!$user) {
            return null;
        }

        if ($guard === 'web' && $user->hasRole('superadmin')) {
            return null;
        }

        return $user->company_id ?? null;
    }
}

if (!function_exists('is_guard_route')) {
    function is_guard_route(string $path): bool
    {
        $user = current_user();
        $guard = getCurrentGuard(); // 'web', 'doctor', etc.

        $prefix = '';

        if ($guard === 'web') {
            if ($user?->hasRole('manager')) {
                $prefix = 'manager/';
            }
            // superadmin gets no prefix
        } elseif ($guard) {
            $prefix = $guard . '/';
        }

        return \Illuminate\Support\Facades\Request::is($prefix . $path . '*');
    }
}

if (!function_exists('globalNotificationRecipients')) {
    function globalNotificationRecipients(): array
    {
        $emails = \App\Models\Configuration::getValue('global_notification_emails', '');

        // Assuming emails stored as comma separated
        $emails = explode(',', $emails);

        // Trim each email and filter empty
        $emails = array_filter(array_map('trim', $emails));

        return $emails;
    }
}

function getCurrentUserNotifications()
{
    foreach (['doctor', 'patient', 'clinic', 'web'] as $guard) {
        if (auth($guard)->check()) {
            return auth($guard)->user()->unreadNotifications;
        }
    }

    return collect(); // empty if unauthenticated
}





