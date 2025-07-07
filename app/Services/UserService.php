<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserService
{
    public function createUserForModel(string $name, string $email, string $roleName, int $relatedId, string $relatedType): User
    {
        $user = User::where('email', $email)->first();

        if ($user) {
            if (!$user->userable_id || !$user->userable_type) {
                $user->update([
                    'userable_id' => $relatedId,
                    'userable_type' => $relatedType,
                ]);
            }
          
            if (!$user->hasRole($roleName)) {
                $user->assignRole($roleName);
            }
            return $user;
        }

        $randomPassword = Str::random(12);
        
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($randomPassword),
            'userable_id' => $relatedId,
            'userable_type' => $relatedType,
        ]);

        $user->assignRole($roleName);

        Password::broker()->sendResetLink(['email' => $email]);
        // $res = Password::broker()->sendResetLink(['email' => $email]);

        // if ($res == Password::RESET_LINK_SENT) {
        //     dd('Reset link sent successfully');
        // } elseif ($res == Password::INVALID_USER) {
        //     dd('No user found with that email');
        // } else {
        //     dd('Other error: ' . $res);
        // }
        return $user;
    }

    public function updateUserForModel(int $relatedId, string $relatedType, array $attributes): ?User
    {
        // Try to find user by relation
        $user = User::where('userable_id', $relatedId)
                    ->where('userable_type', $relatedType)
                    ->first();

        // If not found, try by email (in case relation is missing)
        if (!$user && isset($attributes['email'])) {
            $user = User::where('email', $attributes['email'])->first();

            // Link the user if found by email
            if ($user && (!$user->userable_id || !$user->userable_type)) {
                $user->update([
                    'userable_id' => $relatedId,
                    'userable_type' => $relatedType,
                ]);
            }
        }

        // Still not found? Create new
        if (!$user && isset($attributes['name'], $attributes['email'])) {
            return $this->createUserForModel(
                $attributes['name'],
                $attributes['email'],
                $attributes['role'] ?? 'patient',
                $relatedId,
                $relatedType
            );
        }

        if (!$user) {
            return null;
        }

        // Sync fields
        $updateData = [];
        if (isset($attributes['name'])) {
            $updateData['name'] = $attributes['name'];
        }
        if (isset($attributes['email']) && $attributes['email'] !== $user->email) {
            $updateData['email'] = $attributes['email'];
        }

        if ($updateData) {
            $user->update($updateData);
        }

        // Sync role if needed
        if (isset($attributes['role']) && !$user->hasRole($attributes['role'])) {
            $user->syncRoles([$attributes['role']]);
        }

        return $user;
    }
}