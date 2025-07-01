<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GenerateCrudPermissions extends Command
{
    protected $signature = 'generate:permissions {name}';
    protected $description = 'Generate CRUD permissions for a given resource name';

    public function handle()
    {
        $name = $this->argument('name');
        $actions = ['list', 'create', 'edit', 'delete'];
        $guard = 'web';
        $now = Carbon::now();

        foreach ($actions as $action) {
            $permissionName = strtolower($name) . '-' . $action;

            if (!DB::table('permissions')->where('name', $permissionName)->exists()) {
                DB::table('permissions')->insert([
                    'name' => $permissionName,
                    'guard_name' => $guard,
                    'created_at' => $now,
                    'updated_at' => $now
                ]);

                $this->info("Permission '{$permissionName}' created.");
            } else {
                $this->warn("Permission '{$permissionName}' already exists.");
            }
        }
    }
}
