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
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();

            // Basic info
            $table->string('code')->unique();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('mrn')->nullable();
            $table->string('planner_seq')->nullable();
            $table->enum('clinic_type', ['clinic', 'hospital'])->default('clinic');

            // Weekly schedule and day toggles
            $days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
            foreach ($days as $day) {
                $table->boolean("{$day}")->default(false)->comment("{$day} active?");
                $table->time("{$day}_start_am")->nullable();
                $table->time("{$day}_finish_am")->nullable();

                $table->time("{$day}_start_pm")->nullable();
                $table->time("{$day}_finish_pm")->nullable();
                $table->integer("{$day}_interval")->nullable()->comment('interval in minutes');
            }

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinics');
    }
};
