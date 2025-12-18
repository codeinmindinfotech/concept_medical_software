<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
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
        $guards  = ['web','patient'];

        /* -------------------------------------------------------
         * 1️⃣ CREATE GLOBAL PERMISSIONS (company_id = null)
         * ------------------------------------------------------*/
        foreach ($entities as $entity) {
            foreach ($actions as $action) {
                foreach ($guards as $guard) {
                    Permission::updateOrCreate([
                        'name'       => "{$entity}-{$action}",
                        'guard_name' => $guard,
                        'company_id' => null,
                    ]);
                }
            }
        }

        /* -------------------------------------------------------
         * 2️⃣ CREATE COMPANY-SPECIFIC PERMISSIONS
         * ------------------------------------------------------*/
        $companies = Company::all();

        foreach ($companies as $company) {
            foreach ($entities as $entity) {
                foreach ($actions as $action) {
                    foreach ($guards as $guard) {
                        Permission::updateOrCreate([
                            'name'       => "{$entity}-{$action}",
                            'guard_name' => $guard,
                            'company_id' => $company->id,
                        ]);
                    }
                }
            }
        }

        /* -------------------------------------------------------
         * 3️⃣ CREATE ROLES
         * ------------------------------------------------------*/
        // Superadmin: global role
        Role::firstOrCreate([
            'name'       => 'superadmin',
            'guard_name' => 'web',
            'company_id' => null,
        ]);

        // Web roles: manager, consultant (company-specific)
        $webRoles = ['manager', 'consultant'];
        foreach ($companies as $company) {
            foreach ($webRoles as $roleName) {
                Role::firstOrCreate([
                    'name'       => $roleName,
                    'guard_name' => 'web',
                    'company_id' => $company->id,
                ]);
            }
        }

        // Patient role (company-specific)
        foreach ($companies as $company) {
            Role::firstOrCreate([
                'name'       => 'patient',
                'guard_name' => 'patient',
                'company_id' => $company->id,
            ]);
        }

        /* -------------------------------------------------------
         * 4️⃣ ASSIGN PERMISSIONS TO ROLES
         * ------------------------------------------------------*/
        // Superadmin: all web permissions
        $superadmin = Role::findByName('superadmin','web');
        $superadmin->syncPermissions(Permission::where('guard_name','web')->get());

        // Company-specific managers and consultants
        foreach ($companies as $company) {
            // Manager
            $manager = Role::where('name','manager')
                           ->where('company_id',$company->id)
                           ->first();

            if ($manager) {
                $managerPermissions = Permission::where('guard_name','web')
                    ->where('company_id', $company->id)
                    ->whereIn('name', [
                        'consultant-list','consultant-create','consultant-edit','consultant-delete',
                        'patient-list','patient-create','patient-edit','patient-delete',
                        'appointment-list','appointment-create','appointment-edit','appointment-delete'
                    ])
                    ->get();
                $manager->syncPermissions($managerPermissions);
            }

            // Consultant
            $consultant = Role::where('name','consultant')
                              ->where('company_id',$company->id)
                              ->first();

            if ($consultant) {
                $consultantPermissions = Permission::where('guard_name','web')
                    ->where('company_id', $company->id)
                    ->whereIn('name', [
                        'appointment-list',
                        'patient-list',
                    ])
                    ->get();
                $consultant->syncPermissions($consultantPermissions);
            }

            // Patient
            $patient = Role::where('name','patient')
                           ->where('company_id',$company->id)
                           ->first();

            if ($patient) {
                $patientPermissions = Permission::where('guard_name','patient')
                    ->where('company_id', $company->id)
                    ->get();
                $patient->syncPermissions($patientPermissions);
            }
        }

        /* -------------------------------------------------------
         * 5️⃣ OPTIONAL: User-specific permissions can be assigned later
         * ------------------------------------------------------*/
        // Example:
        // $user = \App\Models\User::find(5);
        // $user->givePermissionTo('appointment-edit');
        // $user->revokePermissionTo('patient-list');
    }
}
