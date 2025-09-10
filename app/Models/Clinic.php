<?php

namespace App\Models;
use App\Traits\BelongsToCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Clinic extends Authenticatable
{
    protected $guarded = [];
    
    use SoftDeletes,HasRoles, BelongsToCompany;
    protected $guard_name = 'clinic';
    protected $casts = [
        'clinic_type' => 'string',
    ];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
