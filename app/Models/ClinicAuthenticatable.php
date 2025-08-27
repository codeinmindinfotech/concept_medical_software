<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class ClinicAuthenticatable extends Authenticatable
{
    public function getConnectionName()
    {
        \Log::info('clinic_db_connection:', ['connection' => session('clinic_db_connection')]);
        return session('clinic_db_connection', config('database.default'));
    }
}
