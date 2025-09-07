<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],
    

    'guards' => [
        'superadmin' => [
            'driver' => 'session',
            'provider' => 'users', // superadmin
        ],

        'manager' => [
            'driver' => 'session',
            'provider' => 'users', // manager
        ],

        'clinic' => [
            'driver' => 'session',
            'provider' => 'clinics',
        ],

        'doctor' => [
            'driver' => 'session',
            'provider' => 'doctors',
        ],

        'patient' => [
            'driver' => 'session',
            'provider' => 'patients',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,  // Superadmin
        ],

        // 'managers' => [  // 👈 new
        //     'driver' => 'eloquent',
        //     'model' => App\Models\User::class, // same model, but for tenant DB
        // ],

        'clinics' => [
            'driver' => 'eloquent',
            'model' => App\Models\Clinic::class,
        ],

        'doctors' => [
            'driver' => 'eloquent',
            'model' => App\Models\Doctor::class,
        ],

        'patients' => [
            'driver' => 'eloquent',
            'model' => App\Models\Patient::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',  // default table for user password resets
            'expire' => 60,
            'throttle' => 60,
        ],

        'clinics' => [
            'provider' => 'clinics',
            'table' => 'password_resets',  // same table, or separate if you want
            'expire' => 60,
            'throttle' => 60,
        ],

        'doctors' => [
            'provider' => 'doctors',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],

        'patients' => [
            'provider' => 'patients',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],



    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
