<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            
            'dropdown-list',
            'dropdown-create',
            'dropdown-edit',
            'dropdown-delete',
            
            'dropdownvalue-list',
            'dropdownvalue-create',
            'dropdownvalue-edit',
            'dropdownvalue-delete',
            
            'doctor-list',
            'doctor-create',
            'doctor-edit',
            'doctor-delete',

            'patient-list',
            'patient-create',
            'patient-edit',
            'patient-delete',

            'consultant-list',
            'consultant-create',
            'consultant-edit',
            'consultant-delete',

            'insurance-list',
            'insurance-create',
            'insurance-edit',
            'insurance-delete'
         ];
         
         foreach ($permissions as $permission) {
              Permission::create(['name' => $permission]);
         }
    }
}
