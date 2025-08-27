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
        foreach (['superadmin', 'clinic', 'doctor', 'patient'] as $guard) {
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
        foreach (['superadmin', 'clinic', 'doctor', 'patient'] as $guard) {
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

        // Auto-wrap single ID into array if needed
        if (!is_array($params)) {
            $params = [$params];
        }

        return route($prefix . $route, $params);
    }
}

if (!function_exists('switchToClinicDatabase')) {
    function switchToClinicDatabase($clinic)
    {

        $connection = [
            'driver'    => 'mysql',
            'host'      => $clinic->db_host ?? '127.0.0.1',
            'port'      => $clinic->db_port ?? '3306',
            'database'  => $clinic->db_database,
            'username'  => $clinic->db_username,
            'password'  => $clinic->db_password,
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

        session(['clinic_db_connection' => 'mysql']); 
        \Log::info('Clinic DB connection set in session:', ['connection' => session('clinic_db_connection')]);
        \Log::info('Current DB in use: ' . DB::connection()->getDatabaseName());
        $currentDb = DB::connection('mysql')->getDatabaseName();

        \Log::info('Current DB in usennnn: ' . $currentDb);


        try {
            DB::connection('mysql')->getPdo();
            \Log::info('Successfully connected to clinic DB');
        } catch (\Exception $e) {
            \Log::error('Failed to connect to clinic DB: ' . $e->getMessage());
        }
    }
}

if (!function_exists('user_can')) {
    function user_can(string $permission): bool
    {
        $guard = getCurrentGuard(); // returns 'clinic', 'doctor', etc.
        $user = getLoggedInUser();  // your auth helper

        if (!$guard || !$user) return false;

        $permissions = cache()->remember("permissions_{$guard}_{$user->id}", 3600, function () use ($guard) {
            return \DB::table('role_permissions')
                ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
                ->where('role_permissions.role', $guard)
                ->where('role_permissions.guard_name', $guard)
                ->pluck('permissions.name')
                ->toArray();
        });

        return in_array($permission, $permissions);
    }
}


