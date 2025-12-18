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
        // Schema::create('conversations', function (Blueprint $table) {
        //     $table->id();
        //     $table->enum('type', ['internal']);
        //     $table->unsignedBigInteger('company_id')->nullable();
        //     $table->unsignedBigInteger('created_by'); // user_id
        //     $table->timestamps();
        // });
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->unsignedBigInteger('created_by_id');    // ID of creator
            $table->string('created_by_type');              // 'user' or 'patient'
            $table->unsignedBigInteger('company_id')->nullable(); // nullable for global
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
