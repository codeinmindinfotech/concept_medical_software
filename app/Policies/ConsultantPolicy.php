<?php 
namespace App\Policies;

use App\Models\User;
use App\Models\Consultant;

class ConsultantPolicy
{
    public function view($user, Consultant $consultant)
    {
        return $user->can('consultant-list');
    }

    public function viewAny($user)
    {
        return $user->can('consultant-list');
    }

    public function create($user)
    {
        return $user->can('consultant-create');
    }

    public function update($user, Consultant $consultant)
    {
        return $user->can('consultant-edit');
    }

    public function delete($user, Consultant $consultant)
    {
        return $user->can('consultant-delete');
    }
}