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
        Schema::table('fee_notes', function (Blueprint $table) {
            $table->renameColumn('visit_date', 'procedure_date');

        });
        // Modify the column to be nullable with no default
        Schema::table('fee_notes', function (Blueprint $table) {
            $table->date('procedure_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fee_notes', function (Blueprint $table) {
            $table->renameColumn('procedure_date', 'visit_date');
        });
    }
};
