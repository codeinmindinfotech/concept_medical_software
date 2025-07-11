<?php

namespace App\Policies;

use App\Models\ChargeCode;
use App\Models\User;

class ChargeCodePolicy
{
    public function viewAny(User $user)
    {
        return $user->can('chargecode-list');
    }

    public function view(User $user, ChargeCode $chargecode)
    {
        return $user->can('chargecode-list');
    }

    public function create(User $user)
    {
        return $user->can('chargecode-create');
    }

    public function update(User $user, ChargeCode $chargecode)
    {
        return $user->can('chargecode-edit');
    }

    public function delete(User $user, ChargeCode $chargecode)
    {
        return $user->can('chargecode-delete');
    }
}