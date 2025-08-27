<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ClinicSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DropDownSeeder::class,
            PermissionSeeder::class,
            SmsDefaultMessagesSeeder::class,
            // RoleSeeder::class,
        ]);
    }
}
