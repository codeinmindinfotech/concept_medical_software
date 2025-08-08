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
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('procedure_id')->nullable()->constrained('charge_codes')->onDelete('cascade');
            $table->string('allergy')->nullable();
            $table->date('admission_date')->nullable();
            $table->time('admission_time')->nullable();
            $table->string('operation_duration')->nullable(); 
            $table->string('ward')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['procedure_id']);
            $table->dropColumn([
                'procedure_id',
                'allergy',
                'admission_date',
                'admission_time',
                'operation_duration',
                'ward',
            ]);
        });
    }
};
