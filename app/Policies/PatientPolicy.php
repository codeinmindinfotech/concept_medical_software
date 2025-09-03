<?php 

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;

class PatientPolicy
{
    // public function view(User $user, Patient $patient): bool
    // {
    //     return $this->isOwnerOrAdmin($user, $patient);
    // }

    // public function update(User $user, Patient $patient): bool
    // {
    //     return $this->isOwnerOrAdmin($user, $patient);
    // }

    // public function delete(User $user, Patient $patient): bool
    // {
    //     return $user->hasRole(['admin', 'superadmin']);
    // }

    // protected function isOwnerOrAdmin(User $user, Patient $patient): bool
    // {
    //     if ($user->hasRole(['admin', 'superadmin'])) {
    //         return true;
    //     }

    //     return $user->hasRole('patient') && $user->id === $patient->id;
    // }
}
