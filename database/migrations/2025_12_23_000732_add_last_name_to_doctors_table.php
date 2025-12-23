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
        Schema::table('doctors', function (Blueprint $table) {

            // Add last_name
            $table->string('last_name')->nullable()->after('name');

            // Drop old salutation column (string)
            $table->dropColumn('salutation');
        });

        Schema::table('doctors', function (Blueprint $table) {

            // Recreate salutation as FK
            $table->unsignedBigInteger('salutation')->nullable()->after('last_name');

            $table->foreign('salutation')
                ->references('id')
                ->on('drop_down_values')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {

            // Drop FK first
            $table->dropForeign(['salutation']);

            // Drop salutation FK column
            $table->dropColumn('salutation');

            // Restore old salutation as string (if needed)
            $table->string('salutation')->nullable();

            // Drop last_name
            $table->dropColumn('last_name');
        });
    }

};
