<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $fillable = ['role', 'guard_name', 'permission_id'];

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
