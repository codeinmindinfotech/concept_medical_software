<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1️⃣ Create or find the global superadmin role
        $role = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web',
            'company_id' => null, // global
        ]);

        // 2️⃣ Create or get all global permissions (company_id=null)
        $permissions = Permission::where('guard_name', 'web')
                                 ->whereNull('company_id')
                                 ->get();

        // 3️⃣ Assign all permissions to the role
        $role->syncPermissions($permissions);

        // 4️⃣ Create or find superadmin user
        $user = User::firstOrCreate(
            ['email' => 'niru@codeinmindinfotech.com'],
            [
                'name' => 'Niru Patel',
                'password' => bcrypt('123456'),
                'company_id' => null, // global
            ]
        );

        // 5️⃣ Assign role to user
        if (!$user->hasRole($role)) {
            $user->assignRole($role); // IMPORTANT: pass the Role model
        }

        $this->command->info("✅ Superadmin user seeded successfully with all global permissions!");
    }
}
