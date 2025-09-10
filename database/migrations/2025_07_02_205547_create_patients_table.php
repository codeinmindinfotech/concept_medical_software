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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable(); 
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');  
            // Original fields
            $table->unsignedBigInteger('title_id')->nullable(); // FK to doctors
            $table->foreign('title_id')->references('id')->on('drop_down_values')->onDelete('cascade');
          
            $table->string('surname');
            $table->string('first_name');
            $table->date('dob')->nullable();
            $table->string('password')->nullable();

            $table->unsignedBigInteger('doctor_id')->nullable(); // FK to doctors
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('set null');
            $table->string('gender')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->text('medical_history')->nullable();

            // Updated/new fields
            $table->foreignId('preferred_contact_id')->constrained('drop_down_values')->onDelete('cascade');
            $table->unsignedBigInteger('insurance_id')->nullable(); // FK to insurances table or dropdownvalues
            $table->string('insurance_plan')->nullable();
            $table->string('policy_no')->nullable();

            $table->text('referral_reason')->nullable();
            $table->text('symptoms')->nullable();
            $table->text('patient_needs')->nullable();
            $table->text('allergies')->nullable();
            $table->text('diagnosis')->nullable();

            $table->boolean('rip')->default(false);
            $table->date('rip_date')->nullable();

            $table->boolean('sms_consent')->default(false);
            $table->boolean('email_consent')->default(false);

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('insurance_id')->references('id')->on('insurances')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
