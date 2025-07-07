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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('doctors');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('company')->nullable();
            $table->string('salutation')->nullable();
            $table->string('address')->nullable();
            $table->string('postcode')->nullable();
            $table->string('mobile')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('contact')->nullable();
            $table->string('note')->nullable();

            // Dropdown foreign keys
            $table->unsignedBigInteger('contact_type_id')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();

            $table->foreign('contact_type_id')->references('id')->on('drop_down_values')->onDelete('set null');
            $table->foreign('payment_method_id')->references('id')->on('drop_down_values')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
