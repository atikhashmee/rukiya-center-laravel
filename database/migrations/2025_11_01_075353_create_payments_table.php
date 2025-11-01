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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            // Optional foreign key to user
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');

            // Basic payment info
            $table->string('payment_intent_id')->nullable(); // from Stripe
            $table->string('payment_method_id')->nullable(); // from Stripe
            $table->string('currency', 10)->default('usd');
            $table->integer('amount'); // stored in cents
            $table->string('status')->default('pending'); // pending|succeeded|failed|requires_action

            // Optional metadata / order info
            $table->string('description')->nullable();
            $table->string('order_id')->nullable();

            // Response data / logs
            $table->json('metadata')->nullable();
            $table->json('response_payload')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
