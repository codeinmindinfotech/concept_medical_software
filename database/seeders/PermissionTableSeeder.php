<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entities = [
            'role',
            'dropdown',
            'dropdownvalue',
            'doctor',
            'company',
            'patient',
            'consultant',
            'insurance',
            'clinic',
            'chargecode',
            'appointment',
            'configuration'
        ];

        $actions = ['list', 'create', 'edit', 'delete'];

        // Define the guards you support
        $guards = ['web', 'doctor', 'patient', 'clinic'];

        // Generate all permissions for all guards
        foreach ($entities as $entity) {
            foreach ($actions as $action) {
                $permissionName = "{$entity}-{$action}";

                foreach ($guards as $guard) {
                    Permission::firstOrCreate([
                        'name'       => $permissionName,
                        'guard_name' => $guard,
                    ]);
                }
            }
        }
    }
}
