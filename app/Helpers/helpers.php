<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

if (!function_exists('format_date')) {
    /**
     * Format a date string or Carbon instance to 'd M Y' or return a default value.
     *
     * @param  string|\DateTimeInterface|null  $date
     * @param  string  $format
     * @param  string  $default
     * @return string
     */
    function format_date($date, $format = 'd M Y', $default = '-')
    {
        if (!$date) {
            return $default;
        }

        // If it's a Carbon or DateTime instance, format directly
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
    /**
     * Format a time string or Carbon instance.
     *
     * @param  \DateTimeInterface|string|null  $time
     * @param  string  $format
     * @param  string  $default
     * @return string
     */
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

if (!function_exists('getLoggedInUser')) {
    function getLoggedInUser()
    {
        foreach (['superadmin', 'clinic', 'doctor', 'patient','manager'] as $guard) {
            if (Auth::guard($guard)->check()) {
                return Auth::guard($guard)->user();
            }
        }

        return null;
    }
}

if (!function_exists('getCurrentGuard')) {
    function getCurrentGuard()
    {
        foreach (['superadmin', 'clinic', 'doctor', 'patient','manager'] as $guard) {
            if (Auth::guard($guard)->check()) {
                return $guard;
            }
        }

        return null;
    }
}

if (!function_exists('guard_route')) {
    function guard_route(string $route, $params = []): string
    {
        $guard = getCurrentGuard();
        $prefix = ($guard && $guard !== 'superadmin') ? $guard . '.' : '';
    
        if (!is_array($params)) {
            $params = [$params];
        }

        return route($prefix . $route, $params);
    }
}

if (!function_exists('switchToCompanyDatabase')) {
    function switchToCompanyDatabase($company)
    {

        $connection = [
            'driver'    => 'mysql',
            'host'      => $company->db_host ?? '127.0.0.1',
            'port'      => $company->db_port ?? '3306',
            'database'  => $company->db_database,
            'username'  => $company->db_username,
            'password'  => $company->db_password,
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => true,
            'engine'    => null,
        ];

        \Log::info('Switching DB connection to:', $connection);

        Config::set('database.connections.mysql', $connection);

        DB::purge('mysql');
        DB::reconnect('mysql');

        session(['company_db_connection' => 'mysql']); 
        \Log::info('Clinic DB connection set in session:', ['connection' => session('company_db_connection')]);
        \Log::info('Current DB in use: ' . DB::connection()->getDatabaseName());
        $currentDb = DB::connection('mysql')->getDatabaseName();

        \Log::info('Current DB in usennnn: ' . $currentDb);


        try {
            DB::connection('mysql')->getPdo();

            \Log::info('-----Successfully connected to company DB');
        } catch (\Exception $e) {
            \Log::error('Failed to connect to company DB: ' . $e->getMessage());
        }
    }
}

if (!function_exists('user_can')) {
    function user_can(string $permission, bool $checkSuperAdmin = false): bool
    {
        static $permissionsCache = [];

        $guard = getCurrentGuard();
        $user = getLoggedInUser();

        if (!$guard || !$user) return false;

        // If superadmin check enabled, and user is superadmin, allow
        if ($checkSuperAdmin  && $guard === 'superadmin') {
            return true;
        }
        // Use role ID as cache key
        $cacheKey = "role_{$guard}";

        if (!isset($permissionsCache[$cacheKey])) {
            $role = \DB::table('roles')
                ->where('guard_name', $guard)
                ->first();

            if (!$role) return false;

            $roleId = $role->id;

            $permissionsCache[$cacheKey] = cache()->remember("permissions_role_{$roleId}", 3600, function () use ($roleId) {
                return \DB::table('role_permissions')
                    ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
                    ->where('role_permissions.role_id', $roleId)
                    ->pluck('permissions.name')
                    ->toArray();
            });
        }

        return in_array($permission, $permissionsCache[$cacheKey]);
    }
}

if (!function_exists('globalNotificationRecipients')) {
    function globalNotificationRecipients(): array
    {
        return array_map('trim', explode(',', env('GLOBAL_NOTIFICATION_EMAIL')));
    }
}

if (!function_exists('is_company_user')) {
    function is_company_user(): bool
    {
        return session('is_company_user', false);
    }
}

if (!function_exists('user_name')) {
    function user_name(): string
    {
        return session('user_name');
    }
}
