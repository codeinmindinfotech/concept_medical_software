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
        $entities = [
            'role',
            'dropdown',
            'dropdownvalue',
            'doctor',
            'patient',
            'consultant',
            'insurance',
            'clinic',
            'chargecode',
            'appointment'
        ];

        $actions = ['list', 'create', 'edit', 'delete'];

        foreach ($entities as $entity) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$entity}-{$action}"]);
            }
        }
    }
}
