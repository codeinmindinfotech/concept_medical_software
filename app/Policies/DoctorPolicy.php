<?php 

namespace App\Policies;

use App\Models\User;
use App\Models\Doctor;

class DoctorPolicy
{
    public function view($user, Doctor $doctor)
    {
        return $user->can('doctor-list');
    }

    public function viewAny($user)
    {
        return $user->can('doctor-list');
    }

    public function create($user)
    {
        return $user->can('doctor-create');
    }

    public function update($user, Doctor $doctor)
    {
        return $user->can('doctor-edit');
    }

    public function delete($user, Doctor $doctor)
    {
        return $user->can('doctor-delete');
    }
}

