<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TenantUser extends Authenticatable
{
    public function getConnectionName()
    {
        return session('company_db_connection', 'mysql');
    }
}
