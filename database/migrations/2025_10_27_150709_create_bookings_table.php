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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            // Patient/Customer Information
            // Link to the customers table. Nullable for guest bookings.
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');

            $table->string('full_name');
            $table->string('email')->index();

            // Conditional Field (e.g., Mother's Name for Istekhara).
            $table->string('mother_name')->nullable();

            // Inquiry Details
            $table->text('inquiry_description');

            // Service Details
            $table->string('service_id')->index();
            $table->string('price_type')->default('FIXED')->comment('FIXED, DONATION, FREE, RESERVATION');

            // Financial & Status
            $table->decimal('service_price', 8, 2)->default(0.00)->comment('The actual price or donation amount received/due.');
            $table->string('payment_status')->default('pending')->comment('pending, paid, failed, assessment_required');
            $table->string('booking_status')->default('new')->comment('new, confirmed, in_progress, completed, cancelled');
            $table->string('phone_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
