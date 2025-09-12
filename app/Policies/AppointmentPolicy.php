<?php

namespace App\Policies;

use App\Models\Appointment;

class AppointmentPolicy
{
    public function viewAny($user)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('appointment-list');
    }

    public function view($user, Appointment $appointment)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('appointment-list');
    }

    public function create($user)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('appointment-create');
    }

    public function update($user, Appointment $appointment)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('appointment-edit');
    }

    public function delete($user, Appointment $appointment)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('appointment-delete');
    }
}
