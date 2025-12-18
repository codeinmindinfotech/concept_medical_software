<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Company;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $rolesPermissions = [
            'superadmin' => [
                'guard_name' => 'web',
                'permissions' => Permission::where('guard_name','web')->pluck('name')->toArray(),
            ],
            'manager' => [
                'guard_name' => 'web',
                'permissions' => Permission::where('guard_name','web')->pluck('name')->toArray(),
            ],
            'consultant' => [
                'guard_name' => 'web',
                'permissions' => ['patient-list','patient-create','patient-edit','patient-delete','document-list','document-create','document-edit','document-delete','appointment-list','appointment-create','notification-list','notification-create','notification-edit','notification-delete'],
            ],
            'patient' => [
                'guard_name' => 'patient',
                'permissions' => ['appointment-list','appointment-create','appointment-edit','patient-list','patient-edit','notification-list','notification-create','notification-edit','notification-delete'],
            ]
        ];

        // 1️⃣ Global superadmin
        $superadminRole = Role::updateOrCreate(
            ['name' => 'superadmin', 'guard_name' => 'web', 'company_id' => null],
        );
        $superadminPermissions = Permission::where('guard_name','web')->whereNull('company_id')->pluck('id');
        $superadminRole->syncPermissions($superadminPermissions);

        // 2️⃣ Company-specific roles
        $companies = Company::all();
        foreach ($companies as $company) {
            foreach ($rolesPermissions as $roleName => $data) {
                if ($roleName === 'superadmin') continue;

                $role = Role::updateOrCreate(
                    ['name' => $roleName, 'guard_name' => $data['guard_name'], 'company_id' => $company->id]
                );

                $permissionIds = Permission::whereIn('name', $data['permissions'])
                                           ->where('guard_name', $data['guard_name'])
                                           ->where('company_id', $company->id)
                                           ->pluck('id');

                $role->syncPermissions($permissionIds);
            }
        }
    }
}
