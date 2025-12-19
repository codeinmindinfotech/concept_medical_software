<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\User;
use App\Models\Patient;

class FloatingChat extends Component
{
    public $users;
    public $patients;

    public function __construct()
    {
        $authUser = auth()->user();

        $this->users = User::companyOnly()->where('id', '!=', $authUser->id)->get();
        if(getCurrentGuard() === 'patient') {
            $this->patients = Patient::companyOnly()->where('id', '!=', $authUser->id)->get();
        }
        else {
            $this->patients = Patient::companyOnly()->get();
        }
    }

    public function render()
    {
        return view('components.floating-chat');
    }
}

