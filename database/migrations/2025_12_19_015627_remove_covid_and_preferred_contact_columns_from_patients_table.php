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
            // Drop foreign key first
            $table->dropForeign(['preferred_contact_id']);

            // Drop columns
            $table->dropColumn([
                'covid_19_vaccination_date',
                'covid_19_vaccination_note',
                'fully_covid_19_vaccinated',
                'preferred_contact_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Re-add covid columns
            $table->date('covid_19_vaccination_date')->nullable();
            $table->text('covid_19_vaccination_note')->nullable();
            $table->boolean('fully_covid_19_vaccinated')->default(false);

            // Re-add foreign key column
            $table->foreignId('preferred_contact_id')
                ->nullable()
                ->constrained('drop_down_values')
                ->onDelete('cascade');
        });
    }
};
