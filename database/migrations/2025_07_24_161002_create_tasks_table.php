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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_creator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('task_owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('drop_down_values');
            $table->string('subject');
            $table->text('task');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->foreignId('status_id')->constrained('drop_down_values');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
