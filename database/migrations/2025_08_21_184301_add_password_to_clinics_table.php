<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->string('password')->nullable()->after('email');
            // Optional: add DB connection fields for multi-tenancy
            $table->string('db_host')->default('127.0.0.1')->after('password');
            $table->string('db_port')->default('3306')->after('db_host');
            $table->string('db_database')->default('null')->after('db_port');
            $table->string('db_username')->default('null')->after('db_database');
            $table->string('db_password')->default('null')->after('db_username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn([
                'password',
                'db_host',
                'db_port',
                'db_database',
                'db_username',
                'db_password',
            ]);
        });
    }
};
