<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropUnique('permissions_name_guard_name_unique'); // drop old constraint
            $table->unique(['name', 'guard_name', 'company_id'], 'permissions_name_guard_company_unique');
        });
    }

    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropUnique('permissions_name_guard_company_unique');
            $table->unique(['name', 'guard_name'], 'permissions_name_guard_name_unique');
        });
    }

};
