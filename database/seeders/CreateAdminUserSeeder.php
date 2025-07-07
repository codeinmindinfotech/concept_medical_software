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

        // Create or find the superadmin role
        $role = Role::firstOrCreate(['name' => 'superadmin']);

        // Get all permissions
        $permissions = Permission::pluck('id', 'id')->all();

        // Assign all permissions to the role
        $role->syncPermissions($permissions);

        // Assign role to the user if not already assigned
        if (!$user->hasRole($role->name)) {
            $user->assignRole($role->name);
        }
    }
}