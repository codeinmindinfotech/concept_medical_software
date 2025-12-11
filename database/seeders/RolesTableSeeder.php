<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\Company;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        /* -------------------------------------------------------
         * 1️⃣ CREATE GLOBAL SUPERADMIN ROLE (company_id = null)
         * ------------------------------------------------------*/
        Role::updateOrCreate(
            ['name' => 'superadmin', 'guard_name' => 'web'],
            ['company_id' => null]
        );

        /* -------------------------------------------------------
         * 2️⃣ COMPANY-SPECIFIC ROLES
         * ------------------------------------------------------*/
        $roleDefinitions = [
            ['name' => 'manager', 'guard_name' => 'web'],
            ['name' => 'clinic', 'guard_name' => 'clinic'],
            ['name' => 'doctor', 'guard_name' => 'doctor'],
            ['name' => 'patient', 'guard_name' => 'patient'],
        ];

        $companies = Company::all();

        foreach ($companies as $company) {
            foreach ($roleDefinitions as $role) {
                Role::updateOrCreate(
                    [
                        'name'       => $role['name'],
                        'guard_name' => $role['guard_name'],
                        'company_id' => $company->id
                    ],
                    [] // no need for values — updateOrCreate only updates changed fields
                );
            }
        }
    }
}
