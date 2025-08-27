<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Define roles and guards
        $roles = [
            'superadmin' => 'superadmin',
            'clinic'     => 'clinic',
            'doctor'     => 'doctor',
            'patient'    => 'patient',
        ];

        // Define permissions per role
        $permissionsByRole = [
            'superadmin' => [ // for 'web' guard
                'role-list', 'role-create', 'role-edit', 'role-delete',
                'user-list', 'user-create', 'user-edit', 'user-delete',
                'dropdown-list', 'dropdown-create', 'dropdown-edit', 'dropdown-delete',
                'doctor-list', 'doctor-create', 'doctor-edit', 'doctor-delete',
                'patient-list', 'patient-edit',
                'appointment-list', 'appointment-create', 'appointment-edit', 'appointment-delete',
                'clinic-list', 'clinic-create', 'clinic-edit', 'clinic-delete',
                'chargecode-list', 'chargecode-create', 'chargecode-edit', 'chargecode-delete',
            ],
            'clinic' => [
                'role-list', 'role-create', 'role-edit', 'role-delete',
                'user-list',
                'dropdown-list', 'dropdown-create', 'dropdown-edit', 'dropdown-delete',
                'doctor-list', 'doctor-create', 'doctor-edit', 'doctor-delete',
                'patient-list', 'patient-edit',
                'appointment-list', 'appointment-create', 'appointment-edit', 'appointment-delete',
                'clinic-list', 'clinic-create', 'clinic-edit', 'clinic-delete',
                'chargecode-list', 'chargecode-create', 'chargecode-edit', 'chargecode-delete',
            ],
            'doctor' => [
                'appointment-list', 'appointment-edit',
                'patient-list',
            ],
            'patient' => [
                'patient-list', 'patient-edit',
                'appointment-list', 'appointment-create',
            ],
        ];

        // Loop through each role
        // foreach ($roles as $roleName => $guardName) {
        //     $role = Role::firstOrCreate([
        //         'name' => $roleName,
        //         'guard_name' => $guardName
        //     ]);

        //     // Create and assign permissions
        //     $permissions = $permissionsByRole[$roleName] ?? [];

        //     foreach ($permissions as $perm) {
        //         Permission::firstOrCreate([
        //             'name' => $perm,
        //             'guard_name' => $guardName,
        //         ]);
        //     }

        //     $role->syncPermissions(
        //         Permission::whereIn('name', $permissions)->where('guard_name', $guardName)->get()
        //     );
        // }
    }
}