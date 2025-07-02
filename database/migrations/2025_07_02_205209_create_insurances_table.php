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
        Schema::create('insurances', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('code')->unique(); // Unique insurance code
            $table->string('address')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact')->nullable();
            $table->string('email')->nullable();
            $table->string('postcode')->nullable();
            $table->string('fax')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance');
    }
};
