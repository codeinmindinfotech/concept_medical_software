<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or find the admin user
        $user = User::firstOrCreate(
            ['email' => 'niru@codeinmindinfotech.com'],
            [
                'name' => 'Niru Patel',
                'password' => bcrypt('123456')  // default password
            ]
        );

        // Create or find the superadmin role with correct guard
        $role = Role::firstOrCreate(
            ['name' => 'superadmin', 'guard_name' => 'web']
        );

        // Get all permissions (web guard only)
        $permissions = Permission::where('guard_name', 'web')->get();

        // Assign all permissions to the role
        $role->syncPermissions($permissions);

        // Assign role to the user if not already assigned
        if (!$user->hasRole($role->name)) {
            $user->assignRole($role->name);
        }
    }
}