<?php

namespace App\Policies;

use App\Models\Consultant;

class ConsultantPolicy
{
    public function viewAny($user)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('consultant-list');
    }

    public function view($user, Consultant $consultant)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('consultant-list');
    }

    public function create($user)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('consultant-create');
    }

    public function update($user, Consultant $consultant)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('consultant-edit');
    }

    public function delete($user, Consultant $consultant)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('consultant-delete');
    }
}
