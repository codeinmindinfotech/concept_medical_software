<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name', 'db_host', 'db_port', 'db_database', 'db_username', 'db_password',
    ];
}
