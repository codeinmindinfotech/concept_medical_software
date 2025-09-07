<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $timestamp = Carbon::now();

        DB::transaction(function () use ($timestamp) {
            // Step 1: Define roles
            $roles = [
                'superadmin',
                'clinic',
                'doctor',
                'patient',
                'manager'
            ];

            // Create or update roles with consistent timestamps
            foreach ($roles as $role) {
                DB::table('roles')->updateOrInsert(
                    ['name' => $role, 'guard_name' => $role],
                    ['created_at' => $timestamp, 'updated_at' => $timestamp]
                );
            }

            // Define modules and actions
            $modules = [
                'role', 'user', 'company', 'dropdown', 'doctor',
                'patient', 'appointment', 'clinic', 'chargecode',
                'consultant','insurance'
            ];
            $actions = ['list', 'create', 'edit', 'delete'];

            // Generate permissions
            $permissions = [];
            foreach ($modules as $module) {
                foreach ($actions as $action) {
                    $permissions[] = "{$module}-{$action}";
                }
            }

            // Fetch existing permissions to avoid duplicates
            $existingPermissions = DB::table('permissions')->pluck('name')->toArray();

            // Prepare new permissions to insert
            $newPermissions = [];
            foreach ($permissions as $perm) {
                if (!in_array($perm, $existingPermissions)) {
                    $newPermissions[] = [
                        'name' => $perm,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ];
                }
            }

            // Bulk insert new permissions
            if (!empty($newPermissions)) {
                DB::table('permissions')->insert($newPermissions);
            }

            // Fetch fresh permissions with IDs
            $permissionsMap = DB::table('permissions')->pluck('id', 'name');

            // Step 2: Define permissions by role
            $permissionsByRole = [
                'superadmin' => $permissions, // superadmin gets all
                'manager' => $permissions, // superadmin gets all
                'clinic' => [
                    'role-list', 'role-create', 'role-edit', 'role-delete',
                    'user-list', 'user-create', 'user-edit', 'user-delete',
                    'dropdown-list', 'dropdown-create', 'dropdown-edit', 'dropdown-delete',
                    'doctor-list', 'doctor-create', 'doctor-edit', 'doctor-delete',
                    'patient-list', 'patient-edit','patient-create', 'patient-delete',
                    'appointment-list', 'appointment-create', 'appointment-edit', 'appointment-delete',
                    'clinic-list', 'clinic-create', 'clinic-edit', 'clinic-delete',
                    'consultant-list', 'consultant-create', 'consultant-edit', 'consultant-delete',
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

            // Fetch roles with IDs
            $rolesMap = DB::table('roles')->pluck('id', 'name');

            // Prepare role_permissions entries
            $rolePermissionsToInsert = [];

            foreach ($permissionsByRole as $roleName => $perms) {
                if (!isset($rolesMap[$roleName])) {
                    continue;
                }
                $roleId = $rolesMap[$roleName];

                foreach ($perms as $permName) {
                    if (!isset($permissionsMap[$permName])) {
                        continue;
                    }
                    $permissionId = $permissionsMap[$permName];

                    $rolePermissionsToInsert[] = [
                        'role_id' => $roleId,
                        'permission_id' => $permissionId,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ];
                }
            }

            // Remove duplicates from rolePermissionsToInsert
            $rolePermissionsToInsert = collect($rolePermissionsToInsert)
                ->unique(function ($item) {
                    return $item['role_id'].'-'.$item['permission_id'];
                })->values()->all();

            // Fetch existing role_permissions to avoid duplicates
            $existingRolePerms = DB::table('role_permissions')
                ->select('role_id', 'permission_id')
                ->get()
                ->map(fn($rp) => $rp->role_id.'-'.$rp->permission_id)
                ->toArray();

            // Filter only new role_permissions
            $newRolePerms = array_filter($rolePermissionsToInsert, function ($rp) use ($existingRolePerms) {
                return !in_array($rp['role_id'].'-'.$rp['permission_id'], $existingRolePerms);
            });

            // Bulk insert new role_permissions
            if (!empty($newRolePerms)) {
                DB::table('role_permissions')->insert($newRolePerms);
            }
        });
    }
}
