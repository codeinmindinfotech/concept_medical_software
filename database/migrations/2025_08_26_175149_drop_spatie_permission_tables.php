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
            Schema::dropIfExists('model_has_roles');
            Schema::dropIfExists('model_has_permissions');
            Schema::dropIfExists('role_has_permissions');
            Schema::dropIfExists('roles');
            Schema::dropIfExists('permissions');
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       
    }
};
