<?php

namespace App\Models;
use App\Traits\BelongsToCompany;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use BelongsToCompany, HasFactory, Notifiable, HasRoles;
    protected  $guard_name = 'web';
    protected $fillable = [
        'company_id',
        'name',
        'email',
        'password',
        'created_by',
        'updated_by'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ClinicResetPasswordNotification($token, $this->company_id, 'user'));
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
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
        // Superadmin has global permissions
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


    public function conversations()
    {
        return $this->belongsToMany(Conversation::class);
    }

}