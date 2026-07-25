<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('instructor_id')->nullable()->after('service_id')->constrained()->nullOnDelete();
            $table->date('booking_date')->nullable()->after('instructor_id');
            $table->time('booking_time')->nullable()->after('booking_date');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['instructor_id']);
            $table->dropColumn(['instructor_id', 'booking_date', 'booking_time']);
        });
    }
};
