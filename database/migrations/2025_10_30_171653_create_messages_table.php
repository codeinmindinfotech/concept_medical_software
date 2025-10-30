<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            // Company relationship
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');

            // (Optional) link to appointment, if you send from appointments
            $table->unsignedBigInteger('appointment_id')->nullable()->index();

            // WhatsApp message fields
            $table->string('to'); // recipient phone number
            $table->enum('direction', ['outgoing', 'incoming']);
            $table->string('type')->default('text'); // text, image, document, etc.
            $table->text('content')->nullable(); // message text or caption
            $table->json('response')->nullable(); // store WhatsApp API / webhook payload

            $table->timestamps();

            // optional foreign key if appointments table exists
            // $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }

};
