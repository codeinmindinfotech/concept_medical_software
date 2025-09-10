<?php 

use App\Models\Appointment;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AppointmentPolicy
{
    public function viewAny(Authenticatable $user)
    {
        return $user->can('appointment-list');
    }

    public function view(Authenticatable $user, Appointment $appointment)
    {
        return $user->can('appointment-list');
    }

    public function create(Authenticatable $user)
    {
        return $user->can('appointment-create');
    }

    public function update(Authenticatable $user, Appointment $appointment)
    {
        return $user->can('appointment-edit');
    }

    public function delete(Authenticatable $user, Appointment $appointment)
    {
        return $user->can('appointment-delete');
    }
}
