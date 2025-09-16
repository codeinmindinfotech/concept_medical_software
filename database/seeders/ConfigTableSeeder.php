<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            ['key' => 'global_notification_emails', 'value' => 'codeinmindinfotech.dev@gmail.com,clive.connolly@gmail.com,nilam.niru@gmail.com'],     // For User model
        ];

        foreach ($configs as $config) {
            Configuration::firstOrCreate($config);
        }
    }
}
