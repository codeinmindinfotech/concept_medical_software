<?php
namespace App\Policies;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Doctor;

class DoctorPolicy
{
    public function viewAny(Authenticatable $user)
    {
        return $user->can('doctor-list');
    }

    public function view(Authenticatable $user, Doctor $doctor)
    {
        return $user->can('doctor-list');
    }

    public function create(Authenticatable $user)
    {
        return $user->can('doctor-create');
    }

    public function update(Authenticatable $user, Doctor $doctor)
    {
        return $user->can('doctor-edit');
    }

    public function delete(Authenticatable $user, Doctor $doctor)
    {
        return $user->can('doctor-delete');
    }
}
