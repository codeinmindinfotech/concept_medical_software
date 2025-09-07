<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantModel extends Model
{
    public function getConnectionName()
    {
        return session('company_db_connection', 'mysql');
    }
}
