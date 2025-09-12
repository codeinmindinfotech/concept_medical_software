<?php

namespace App\Models;
use App\Traits\BelongsToCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;


class Clinic extends Authenticatable
{
    use SoftDeletes,HasRoles, BelongsToCompany, Notifiable;

    protected $guarded = [];
    protected $guard_name = 'clinic';
    protected $casts = [
        'clinic_type' => 'string',
    ];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ClinicResetPasswordNotification($token, $this->company_id, $this->guard_name));
    }

}
