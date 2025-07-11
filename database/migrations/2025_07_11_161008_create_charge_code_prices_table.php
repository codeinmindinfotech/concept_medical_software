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
        Schema::create('charge_code_prices', function (Blueprint $table) {
            $table->id();

            $table->decimal('price', 10, 2)->default(0.00);

            // Foreign key to chargecode
            $table->unsignedBigInteger('charge_code_id');
            $table->foreign('charge_code_id')->references('id')->on('chargecodes')->onDelete('cascade');

            // Foreign key to insurances
            $table->unsignedBigInteger('insurance_id');
            $table->foreign('insurance_id')->references('id')->on('insurances')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charge_code_prices');
    }
};
