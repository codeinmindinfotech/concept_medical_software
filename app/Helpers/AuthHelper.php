<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class AuthHelper
{
    public static function getAuthenticatedUserAndGuard(): array
    {
        foreach (array_keys(config('auth.guards')) as $guard) {
            if (Auth::guard($guard)->check()) {
                return [Auth::guard($guard)->user(), $guard];
            }
        }

        return [null, null];
    }

    public static function isRole(string $role): bool
    {
        [, $guard] = self::getAuthenticatedUserAndGuard();
        return $guard === $role;
    }

    public static function user()
    {
        [$user, ] = self::getAuthenticatedUserAndGuard();
        return $user;
    }

    public static function guard()
    {
        [, $guard] = self::getAuthenticatedUserAndGuard();
        return $guard;
    }
}
