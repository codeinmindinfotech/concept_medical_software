<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class ClinicAuthenticatable extends Authenticatable
{
    public function getConnectionName()
    {
        return session('clinic_db_connection', config('database.default'));
    }
}
