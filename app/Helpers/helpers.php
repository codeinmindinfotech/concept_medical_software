<?php

use Carbon\Carbon;

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