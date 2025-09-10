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
        Schema::create('consultant_insurance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();  
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');  
            $table->foreignId('consultant_id')->constrained()->onDelete('cascade');
            $table->foreignId('insurance_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultant_insurance');
    }
};
