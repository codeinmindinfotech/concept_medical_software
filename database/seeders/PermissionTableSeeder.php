<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\Company;

class PermissionTableSeeder extends Seeder
{
    public function run(): void
    {
        $entities = [
            'role','dropdown','document','dropdownvalue','doctor','company',
            'patient','consultant','insurance','clinic','chargecode',
            'appointment','configuration','notification'
        ];

        $actions = ['list', 'create', 'edit', 'delete'];
        $guards  = ['web','doctor','patient','clinic'];

        /* -------------------------------------------------------
         * 1️⃣ GLOBAL PERMISSIONS (company_id = null)
         * ------------------------------------------------------*/
        foreach ($entities as $entity) {
            foreach ($actions as $action) {
                foreach ($guards as $guard) {

                    Permission::updateOrCreate(
                        [
                            'name'       => "{$entity}-{$action}",
                            'guard_name' => $guard,
                            'company_id' => null,
                        ],
                        []
                    );
                }
            }
        }

        /* -------------------------------------------------------
         * 2️⃣ COMPANY-SPECIFIC PERMISSIONS
         * ------------------------------------------------------*/
        $companies = Company::all();   // ← LOAD ALL COMPANIES

        foreach ($companies as $company) {
            foreach ($entities as $entity) {
                foreach ($actions as $action) {
                    foreach ($guards as $guard) {

                        Permission::updateOrCreate(
                            [
                                'name'       => "{$entity}-{$action}",
                                'guard_name' => $guard,
                                'company_id' => $company->id,
                            ],
                            []
                        );
                    }
                }
            }
        }
    }
}
