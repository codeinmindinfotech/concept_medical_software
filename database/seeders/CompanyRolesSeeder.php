<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CompanyRolesSeeder extends Seeder
{
    public function run(): void
    {
        // Global superadmin
        Role::updateOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web',
            'company_id' => null
        ]);

        // Example company roles
        $companies = \App\Models\Company::all();

        foreach ($companies as $company) {
            Role::updateOrCreate([
                'name' => 'manager',
                'guard_name' => 'web',
                'company_id' => $company->id
            ]);

            Role::updateOrCreate([
                'name' => 'consultant',
                'guard_name' => 'web',
                'company_id' => $company->id
            ]);

            Role::updateOrCreate([
                'name' => 'patient',
                'guard_name' => 'patient',
                'company_id' => $company->id
            ]);
        }
//         $managerId = 7; 
// $manager = \App\Models\User::find($managerId); // web guard

// // Check if superadmin
// if ($manager->hasRole('superadmin')) {
//     // Assign all global permissions (company_id = null) for web guard
//     $permissions = Permission::where('company_id', null)
//                              ->where('guard_name', 'web')
//                              ->get();

//     $manager->syncPermissions($permissions);
// } else {
//     // Get the manager's role for their company
//     $role = Role::where('name', 'manager')
//                 ->where('guard_name', 'web')
//                 ->where('company_id', $manager->company_id)
//                 ->first();

//     if ($role) {
//         // Assign the role to the manager
//         $manager->assignRole($role);

//         // Fetch company-specific permissions for the web guard
//         $permissions = Permission::where('company_id', $manager->company_id)
//                                  ->where('guard_name', 'web')
//                                  ->get();

//         // Assign all company-specific permissions to the manager
//         $manager->syncPermissions($permissions);
//     }
// }



    }
}
