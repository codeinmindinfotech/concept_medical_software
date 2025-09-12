<?php

namespace App\Policies;

use App\Models\Doctor;

class DoctorPolicy
{
    public function viewAny($user)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('doctor-list');
    }

    public function view($user, Doctor $doctor)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('doctor-list');
    }

    public function create($user)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('doctor-create');
    }

    public function update($user, Doctor $doctor)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('doctor-edit');
    }

    public function delete($user, Doctor $doctor)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('doctor-delete');
    }
}
