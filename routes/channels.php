<?php

use Illuminate\Support\Facades\Broadcast;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

Broadcast::channel('patient.{id}', function ($user, $id) {
    Log::info('Broadcast auth user', ['user' => $user]);
    return $user instanceof \App\Models\Patient && (int) $user->id === (int) $id;
});

Broadcast::channel('doctor.{id}', function ($user, $id) {
    Log::info("Broadcast channel auth for guard doctor");
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

Broadcast::channel('consultant.{id}', function ($user, $id) {
    Log::info("Broadcast channel auth for guard user");
    return $user->id == $id && $user instanceof \App\Models\User;
});
?>