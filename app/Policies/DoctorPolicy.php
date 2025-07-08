<?php 

namespace App\Policies;

use App\Models\User;
use App\Models\Doctor;

class DoctorPolicy
{
    public function viewAny(User $user)
    {
        return $user->can('doctor-list');
    }

    public function view(User $user, Doctor $doctor)
    {
        return $user->can('doctor-list');
    }

    public function create(User $user)
    {
        return $user->can('doctor-create');
    }

    public function update(User $user, Doctor $doctor)
    {
        return $user->can('doctor-edit');
    }

    public function delete(User $user, Doctor $doctor)
    {
        return $user->can('doctor-delete');
    }
}
