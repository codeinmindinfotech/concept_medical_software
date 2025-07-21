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
        Schema::table('patient_audio_files', function (Blueprint $table) {
            $table->unsignedBigInteger('doctor_id')->nullable()->after('id');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_audio_files', function (Blueprint $table) {
            $table->dropColumn('doctor_id');
        });
    }
};
