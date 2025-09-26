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
            $table->string('next_of_kin')->after('fully_covid_19_vaccinated');
            $table->string('kin_contact_no')->after('next_of_kin');
            $table->text('kin_address')->after('kin_contact_no');
            $table->unsignedBigInteger('relationship')->after('kin_address')->nullable();
            $table->string('kin_email')->after('relationship')->nullable();
    
            $table->foreign('relationship')
                  ->references('id')
                  ->on('drop_down_values')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['relationship']);
            $table->dropColumn([
                'next_of_kin',
                'kin_contact_no',
                'kin_address',
                'relationship',
                'kin_email'
            ]);
        });
    }
};
