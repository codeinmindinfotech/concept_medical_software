<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicModel extends Model
{
    public function getConnectionName()
    {
        return session('clinic_db_connection', config('database.default'));
    }
}
