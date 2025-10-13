<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $rolesPermissions = [
            'superadmin' => [
                'guard_name' => 'web',
                'permissions' => Permission::where('guard_name', 'web')->pluck('name')->toArray(), 
            ],
            'manager' => [
                'guard_name' => 'web',
                'permissions' => [
                    'patient-list', 'patient-create', 'patient-edit', 'patient-delete',
                    'appointment-list', 'appointment-create','notification-list','notification-create', 'notification-edit', 'notification-delete'
                ],
            ],
            'doctor' => [
                'guard_name' => 'doctor',
                'permissions' => [
                    'appointment-list', 'appointment-create', 'appointment-edit','notification-list','notification-create', 'notification-edit', 'notification-delete',
                    'patient-list',
                ],
            ],
            'patient' => [
                'guard_name' => 'patient',
                'permissions' => [
                    'appointment-list', 'appointment-create', 'appointment-edit',
                    'patient-list','patient-edit','notification-list','notification-create', 'notification-edit', 'notification-delete'
                ],
            ],
            'clinic' => [
                'guard_name' => 'clinic',
                'permissions' => [
                    'doctor-list', 'doctor-create', 'patient-list', 'appointment-list',
                    'appointment-create', 'appointment-edit','notification-list','notification-create', 'notification-edit', 'notification-delete'
                ],
            ],
        ];

        foreach ($rolesPermissions as $roleName => $data) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => $data['guard_name'],
            ]);

            foreach ($data['permissions'] as $permName) {
                $permission = Permission::where('name', $permName)
                                        ->where('guard_name', $data['guard_name']) // ✅ ensure correct guard
                                        ->first();

                if ($permission) {
                    $role->givePermissionTo($permission);
                }
            }
        }
    }
}
