<?php

namespace App\Models;
use App\Traits\BelongsToCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Permission;

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

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'clinic_id');
    }

    public function calendarDays()
    {
        return $this->hasMany(CalendarDay::class);
    }

    public function company_roles()
    {
        return $this->roles()->where('roles.company_id', $this->company_id);
    }

    public function company_permissions()
    {
        return $this->permissions()->where('permissions.company_id', $this->company_id);
    }


    public function hasCompanyRole(string $roleName, ?string $guardName = null): bool
    {
        $guard = $this->guard_name ?: $guardName;

        return $this->roles()
                    ->where('roles.name', $roleName)
                    ->where('roles.guard_name', $guard)
                    ->where('roles.company_id', $this->company_id) // << prefix table
                    ->exists();
    }


    public function hasCompanyPermission(string $permission, ?string $guardName = null): bool
    {
        $guard = $this->guard_name ?: $guardName;
    
        // Superadmin: global
        if ($this->hasCompanyRole('superadmin', $guard)) {
            return $this->permissions()
                        ->where('permissions.name', $permission)
                        ->where('permissions.guard_name', $guard)
                        ->whereNull('permissions.company_id')
                        ->exists();
        }
    
        return $this->permissions()
                    ->where('permissions.name', $permission)
                    ->where('permissions.guard_name', $guard)
                    ->where('permissions.company_id', $this->company_id) // << prefix table
                    ->exists();
    }
    
    public function hasPermissionTo($permission, $guardName = null): bool
    {
        $guardName = $this->guard_name ?: $guardName;
        if ($this->hasRole('superadmin', $guardName)) {
            return Permission::where('name', $permission)
                             ->where('guard_name', $guardName)
                             ->whereNull('company_id') // global
                             ->exists();
        }

        // Normal company user
        return $this->permissions()
                    ->where('permissions.name', $permission)
                    ->where('permissions.guard_name', $guardName)
                    ->where('permissions.company_id', $this->company_id)
                    ->exists();
    }
}
