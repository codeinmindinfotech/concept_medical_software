<?php

use Illuminate\Support\Facades\Broadcast;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
    Log::info("Broadcast channel auth for guard start heere..");

    // Broadcast::channel('test-channel', function ($user) {
    //     Log::info("Test channel auth hit for user {$user->id}");
    //     return true;
    // });
    
    // Broadcast::channel('patient.{id}', function ($user, $id) {
    //     \Log::info("Patient {$user->id} trying to access channel patient.{$id}");

    //     return $user->id == $id;
    // }, ['guards' => ['patient']]);
    
    // Broadcast::channel('doctor.{id}', function ($user, $id) {
    //     \Log::info("doctor {$user->id} trying to access channel patient.{$id}");

    //     return $user->id == $id;
    // }, ['guards' => ['doctor']]);
    
    // Broadcast::channel('clinic.{id}', function ($user, $id) {
    //     \Log::info("clinic {$user->id} trying to access channel patient.{$id}");

    //     return $user->id == $id;
    // }, ['guards' => ['clinic']]);
    
    // Broadcast::channel('web.{id}', function ($user, $id) {
    //     \Log::info("web {$user->id} trying to access channel patient.{$id}");

    //     return $user->id == $id;
    // }, ['guards' => ['web']]);

    
// Broadcast::channel('patient.{id}', function ($user, $id) {
//     // For example, only allow if user is the patient or authorized
//     Log::info("Broadcast channel auth for guard patient");
//     return $user->id == $id && $user instanceof \App\Models\Patient;
// });
Broadcast::channel('patient.{id}', function ($user, $id) {
    logger()->info('Broadcast auth user', ['user' => $user]);

    return $user instanceof \App\Models\Patient && (int) $user->id === (int) $id;
});

Broadcast::channel('doctor.{id}', function ($user, $id) {
    Log::info("Broadcast channel auth for guard doctor");
    logger()->info('Broadcast channel auth for guard doctor', ['user' => $user]);
    return $user->id == $id && $user instanceof \App\Models\Doctor;
});

Broadcast::channel('clinic.{id}', function ($user, $id) {
    Log::info("Broadcast channel auth for guard clinic");
    return $user->id == $id && $user instanceof \App\Models\Clinic;
});

Broadcast::channel('user.{id}', function ($user, $id) {
    Log::info("Broadcast channel auth for guard user");
    return $user->id == $id && $user instanceof \App\Models\User;
});


// Broadcast::channel('{guard}.{id}', function ($user, $guard, $id) {
//     $allowedGuards = ['doctor', 'patient', 'clinic', 'web'];

//     if (!in_array($guard, $allowedGuards)) return false;

//     if ($user && $user->getAuthIdentifier() == $id) {
//         $actualGuard = match (true) {
//             $user instanceof \App\Models\Doctor => 'doctor',
//             $user instanceof \App\Models\Patient => 'patient',
//             $user instanceof \App\Models\Clinic => 'clinic',
//             $user instanceof \App\Models\User => 'web',
//             default => null,
//         };

//         return $guard === $actualGuard;
//     }

//     return false;
// });



?>