<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $patientRole = Role::firstOrCreate(['name' => 'patient']);
        $clinicRole = Role::firstOrCreate(['name' => 'clinic']);

        // Assign permissions to patient
        $patientPermissions = [
            'patient-list',
            'patient-edit',
            // 'appointment-list',
            // 'appointment-create'
        ];
        $patientRole->syncPermissions($patientPermissions);

        // Assign permissions to clinic
        $clinicPermissions = [
            'clinic-list',
            'clinic-edit',
            'clinic-create',
            'clinic-delete',
            // 'appointment-list',
            // 'appointment-edit',
            // 'appointment-create',
            // 'appointment-delete',
            'patient-list'
        ];
        $clinicRole->syncPermissions($clinicPermissions);
    }
}
