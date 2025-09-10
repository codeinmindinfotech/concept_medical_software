<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'superadmin', 'guard_name' => 'web'],     // For User model
            ['name' => 'manager', 'guard_name' => 'web'],        // For User model
            ['name' => 'doctor', 'guard_name' => 'doctor'],      // For Doctor model
            ['name' => 'patient', 'guard_name' => 'patient'],    // For Patient model
            ['name' => 'clinic', 'guard_name' => 'clinic'],      // For Clinic model
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }
    }
}
