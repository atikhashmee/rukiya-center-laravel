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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            // Core Identity Fields
            $table->string('id_code')->unique()->comment('Unique ID for the service option, e.g., ISTEKHARA_DEEP');
            $table->string('category')->index()->comment('Parent service category (counseling, rukiya, istekara)');
            $table->string('title');
            $table->string('tagline');
            $table->text('description');

            // Presentation Fields
            $table->string('icon')->comment('Lucide icon name');
            $table->string('card_color')->comment('Tailwind class for border highlight');
            $table->json('features')->comment('List of key benefits/features');
            $table->unsignedSmallInteger('order')->default(1)->comment('Sort order for display');

            // Booking and Payment Logic
            $table->enum('price_type', ['FREE', 'DONATION', 'FIXED', 'RESERVATION'])->comment('Defines payment method');
            $table->decimal('price_value', 8, 2)->nullable()->comment('Fixed price, if applicable');
            $table->decimal('min_donation', 8, 2)->nullable()->comment('Minimum donation amount, if applicable');
            $table->boolean('requires_custom_assessment')->default(false)->comment('If true, booking is a reservation for later pricing');
            $table->json('required_form_fields')->nullable()->comment('Additional required fields (e.g., motherName, phone)');
            $table->string('submit_button_text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
