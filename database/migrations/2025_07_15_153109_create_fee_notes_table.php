<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fee_notes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->foreignId('consultant_id')->constrained('consultants')->onDelete('cascade');
            $table->foreignId('chargecode_id')->constrained('charge_codes')->onDelete('cascade');

            $table->text('comment')->nullable();
            $table->unsignedBigInteger('narrative');
            $table->foreign('narrative')->references('id')->on('drop_down_values')->onDelete('restrict');
            
            $table->date('admission_date')->nullable();
            $table->date('discharge_date')->nullable();
            $table->date('visit_date')->nullable();

            $table->integer('qty')->default(1);

            $table->decimal('charge_gross', 10, 2)->default(0);
            $table->decimal('reduction_percent', 5, 2)->default(0); // Percentage stored as decimal (e.g., 10.00 = 10%)
            $table->decimal('charge_net', 10, 2)->default(0);
            $table->decimal('vat_rate_percent', 5, 2)->default(0);
            $table->decimal('line_total', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_notes');
    }
};
