<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AppointmentPolicy
{
    public function viewAny(User $user)
    {
        return $user->can('appointment-list');
    }

    public function view(User $user, Appointment $appointment)
    {
        return $user->can('appointment-list');
    }

    public function create(User $user)
    {
        return $user->can('appointment-create');
    }

    public function update(User $user, Appointment $appointment)
    {
        return $user->can('appointment-edit');
    }

    public function delete(User $user, Appointment $appointment)
    {
        return $user->can('appointment-delete');
    }
}
