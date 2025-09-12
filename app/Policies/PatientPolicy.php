<?php

namespace App\Policies;

use App\Models\Patient;

class PatientPolicy
{
    public function viewAny($user)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('patient-list');
    }

    public function view($user, Patient $patient)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('patient-list');
    }

    public function create($user)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('patient-create');
    }

    public function update($user, Patient $patient)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('patient-edit');
    }

    public function delete($user, Patient $patient)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('patient-delete');
    }
}
