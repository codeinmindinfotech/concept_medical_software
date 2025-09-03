<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'guard_name'];

    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permissions',
            'role_id',        // FK on pivot table for Role
            'permission_id',  // FK on pivot table for Permission
            'id',             // Local key on Role model
            'id'              // Local key on Permission model
        )->withTimestamps();
    }

}
