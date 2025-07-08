<?php 
namespace App\Policies;

use App\Models\User;
use App\Models\Consultant;

class ConsultantPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('consultant-list');
    }

    public function view(User $user, Consultant $consultant): bool
    {
        return $user->can('consultant-list');
    }

    public function create(User $user): bool
    {
        return $user->can('consultant-create');
    }

    public function update(User $user, Consultant $consultant): bool
    {
        return $user->can('consultant-edit');
    }

    public function delete(User $user, Consultant $consultant): bool
    {
        return $user->can('consultant-delete');
    }
}