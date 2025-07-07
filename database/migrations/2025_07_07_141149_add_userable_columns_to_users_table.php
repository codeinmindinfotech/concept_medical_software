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
        Schema::table('users', function (Blueprint $table) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('userable_id')->nullable()->after('id');
                $table->string('userable_type')->nullable()->after('userable_id');
                $table->index(['userable_id', 'userable_type'], 'userable_index');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('userable_index');
            $table->dropColumn(['userable_id', 'userable_type']);
        });
    }
};
