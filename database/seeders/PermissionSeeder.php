<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Step 1: Define roles
        $roles = [
            'superadmin',
            'clinic',
            'doctor',
            'patient',
        ];

        // Step 2: Define permissions by role
        $permissionsByRole = [
            'superadmin' => [
                'role-list', 'role-create', 'role-edit', 'role-delete',
                'user-list', 'user-create', 'user-edit', 'user-delete',
                'dropdown-list', 'dropdown-create', 'dropdown-edit', 'dropdown-delete',
                'doctor-list', 'doctor-create', 'doctor-edit', 'doctor-delete',
                'patient-list', 'patient-edit','patient-create', 'patient-delete',
                'appointment-list', 'appointment-create', 'appointment-edit', 'appointment-delete',
                'clinic-list', 'clinic-create', 'clinic-edit', 'clinic-delete',
                'chargecode-list', 'chargecode-create', 'chargecode-edit', 'chargecode-delete',
            ],
            'clinic' => [
                'role-list', 'role-create', 'role-edit', 'role-delete',
                'user-list', 'user-create', 'user-edit', 'user-delete',
                'dropdown-list', 'dropdown-create', 'dropdown-edit', 'dropdown-delete',
                'doctor-list', 'doctor-create', 'doctor-edit', 'doctor-delete',
                'patient-list', 'patient-edit','patient-create', 'patient-delete',
                'appointment-list', 'appointment-create', 'appointment-edit', 'appointment-delete',
                'clinic-list', 'clinic-create', 'clinic-edit', 'clinic-delete',
                'chargecode-list', 'chargecode-create', 'chargecode-edit', 'chargecode-delete',
            ],
            'doctor' => [
                'doctor-list', 
                'appointment-list', 'appointment-edit',
                'patient-list',
            ],
            'patient' => [
                'doctor-list', 
                'patient-list', 'patient-edit',
                'appointment-list', 'appointment-create',
            ],
        ];

        // Step 3: Create roles
        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert([
                'name' => $role,
            ], [
                'guard_name' => 'web', // optional if you track guards
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Step 4: Assign permissions to each role
        foreach ($permissionsByRole as $roleName => $permissions) {
            // Get role ID
            $role = DB::table('roles')->where('name', $roleName)->first();

            if (!$role) {
                continue;
            }

            foreach ($permissions as $permName) {
                // Create or get the permission
                $permission = Permission::firstOrCreate([
                    'name' => $permName,
                    'guard_name' => 'web',
                ]);

                // Assign permission to role in pivot table
                DB::table('role_permissions')->updateOrInsert([
                    'role_id' => $role->id,
                    'permission_id' => $permission->id,
                ]);
            }
        }
    }
}
