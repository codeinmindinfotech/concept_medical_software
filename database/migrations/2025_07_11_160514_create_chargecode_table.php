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
        Schema::create('charge_codes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('company_id')->nullable();             
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');  
            $table->string('code'); 
            $table->unique(['company_id', 'code']);
            // Foreign key to drop_down_values (charge group type)
            $table->unsignedBigInteger('chargeGroupType');
            $table->foreign('chargeGroupType')->references('id')->on('drop_down_values')->onDelete('restrict');
            
            $table->text('description')->nullable();

            $table->decimal('price', 10, 2)->default(0.00);
            $table->unsignedBigInteger('vatcodeid')->nullable(); // if linked to another table
            $table->decimal('vatrate', 5, 2)->default(0.00);

            $table->date('last_price_updated')->nullable();
            $table->decimal('previous_amount', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('charge_codes');
    }
};
