<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('document_templates', function (Blueprint $table) {
            $table->unsignedBigInteger('appointment_type')->nullable()->after('id');

            $table->foreign('appointment_type')
                  ->references('id')
                  ->on('drop_down_values')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('document_templates', function (Blueprint $table) {
            $table->dropForeign(['appointment_type']);
            $table->dropColumn('appointment_type');
        });
    }
};

