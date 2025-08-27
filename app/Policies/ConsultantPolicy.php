<?php 
namespace App\Policies;

use App\Models\Clinic;
use App\Models\User;
use App\Models\Consultant;
use App\Models\Doctor;
use App\Models\Patient;

class ConsultantPolicy
{
    public function viewAny($user): bool
    {
        if ($user instanceof User) {
            return $user->can('consultant-list');
        }
    
        if ($user instanceof Clinic) {
            return $user->can('consultant-list');
        }

        if ($user instanceof Patient) {
            return $user->can('consultant-list');
        }

        if ($user instanceof Doctor) {
            return $user->can('consultant-list');
        }

        return false;
    }

    public function view($user, Consultant $consultant): bool
    {
        if ($user instanceof User) {
            return $user->can('consultant-list');
        }
    
        if ($user instanceof Clinic) {
            return $user->can('consultant-list');
            // return $user->hasPermission('consultant-list');
        }

        if ($user instanceof Patient) {
            return $user->can('consultant-list');
        }

        if ($user instanceof Doctor) {
            return $user->can('consultant-list');
        }

        return false;
    }

    public function create($user): bool
    {
        if ($user instanceof User) {
            return $user->can('consultant-create');
        }
    
        if ($user instanceof Clinic) {
            return $user->can('consultant-create'); // hypothetical method
        }

        if ($user instanceof Patient) {
            return $user->can('consultant-create'); // hypothetical method
        }

        if ($user instanceof Doctor) {
            return $user->can('consultant-create'); // hypothetical method
        }

        return false;
    }

    public function update($user, Consultant $consultant): bool
    {
        if ($user instanceof User) {
            return $user->can('consultant-edit');
        }
    
        if ($user instanceof Clinic) {
            return $user->can('consultant-edit');
        }

        if ($user instanceof Patient) {
            return $user->can('consultant-edit');
        }

        if ($user instanceof Doctor) {
            return $user->can('consultant-edit');
        }

        return false;
    }

    public function delete($user, Consultant $consultant): bool
    {
        if ($user instanceof User) {
            return $user->can('consultant-delete');
        }
    
        if ($user instanceof Clinic) {
            return $user->can('consultant-delete');
        }

        if ($user instanceof Patient) {
            return $user->can('consultant-delete');
        }

        if ($user instanceof Doctor) {
            return $user->can('consultant-delete');
        }

        return false;
    }
}