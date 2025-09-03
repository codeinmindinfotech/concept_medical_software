<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or find the admin user
        $user = User::firstOrCreate(
            ['email' => 'clive.connolly@gmail.com'],
            [
                'name' => 'Clive Connolly',
                'password' => bcrypt('123456')  // default password
            ]
        );

    }
}