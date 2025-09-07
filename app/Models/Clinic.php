<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Clinic extends TenantUser
{
    use SoftDeletes;
    protected $guarded = [];
    protected $guard_name = 'clinic'; 

    protected $casts = [
        'clinic_type' => 'string',
    ];

    // Make sure password is hidden when serialized
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getConnectionName()
    {
        return session('company_db_connection', 'mysql');
    }
}