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
        Schema::create('recalls', function (Blueprint $table) {
            $table->id();            
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->enum('recall_interval', [
                'Today',
                '6 weeks',
                '2 months',
                '3 months',
                '6 months',
                '1 year',
            ]);
            $table->date('recall_date')->nullable();
            $table->foreignId('status_id')->constrained('drop_down_values');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recalls');
    }
};
