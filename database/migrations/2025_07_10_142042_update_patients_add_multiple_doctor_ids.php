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
        Schema::table('patients', function (Blueprint $table) {
            $table->unsignedBigInteger('referral_doctor_id')->nullable()->after('doctor_id');
            $table->unsignedBigInteger('other_doctor_id')->nullable()->after('referral_doctor_id');
            $table->unsignedBigInteger('solicitor_doctor_id')->nullable()->after('other_doctor_id');

            $table->foreign('referral_doctor_id')->references('id')->on('doctors')->nullOnDelete();
            $table->foreign('other_doctor_id')->references('id')->on('doctors')->nullOnDelete();
            $table->foreign('solicitor_doctor_id')->references('id')->on('doctors')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['referral_doctor_id']);
            $table->dropForeign(['other_doctor_id']);
            $table->dropForeign(['solicitor_doctor_id']);

            $table->dropColumn(['referral_doctor_id', 'other_doctor_id', 'solicitor_doctor_id']);
        });
    }
};
