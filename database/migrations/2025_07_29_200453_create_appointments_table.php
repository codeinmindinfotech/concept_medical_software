<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->unsignedBigInteger('appointment_type')->nullable();

            $table->foreign('appointment_type')
                  ->references('id')
                  ->on('drop_down_values')
                  ->onDelete('set null');
                  
            $table->date('appointment_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->tinyInteger('apt_slots')->unsigned()->default(1); // Values from 1 to 10

            $table->text('patient_need')->nullable();
            $table->text('appointment_note')->nullable();
            $table->time('arrival_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};