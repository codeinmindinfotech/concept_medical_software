<?php

namespace App\Policies;

use App\Models\ChargeCode;

class ChargeCodePolicy
{
    public function viewAny($user)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('chargecode-list');
    }

    public function view($user, ChargeCode $chargecode)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('chargecode-list');
    }

    public function create($user)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('chargecode-create');
    }

    public function update($user, ChargeCode $chargecode)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('chargecode-edit');
    }

    public function delete($user, ChargeCode $chargecode)
    {
        $authUser = current_user();
        return $authUser && $authUser->can('chargecode-delete');
    }
}
