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
        Schema::create('consultants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable(); 
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');  
            $table->string('code'); 
            $table->unique(['company_id', 'code']);
            $table->string('name');
            $table->text('address');
            $table->string('phone');
            $table->string('fax')->nullable();
            $table->string('email');
            $table->string('imc_no');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultants');
    }
};
