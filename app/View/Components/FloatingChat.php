<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Collection;

class FloatingChat extends Component
{
    public $users;
    public $patients;

    public function __construct()
    {
        $authUser = auth()->user();

        $this->users = \App\Models\User::companyOnly()->where('id', '!=', $authUser->id)->get();
        if(getCurrentGuard() === 'patient') {
            $this->patients = \App\Models\Patient::companyOnly()->where('id', '!=', $authUser->id)->get();
        }
        else {
            if (getCurrentGuard() === 'web' && current_user()?->hasRole('superadmin')) {
                $this->patients = collect(); 
            } else {
                $this->patients = \App\Models\Patient::companyOnly()->get();
            }
        }
    }

    public function render()
    {
        return view('components.floating-chat');
    }
}

