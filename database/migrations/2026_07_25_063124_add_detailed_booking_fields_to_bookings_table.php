<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('first_name')->after('booking_id')->nullable();
            $table->string('last_name')->after('first_name')->nullable();
            $table->string('phone_country', 2)->after('last_name')->default('GB');
            $table->string('gender')->after('phone_country')->nullable();
            $table->string('language')->after('gender')->nullable();
            $table->string('ethnic_origin')->after('language')->nullable();
            $table->string('age')->after('ethnic_origin')->nullable();
            $table->string('is_first_appointment')->after('age')->nullable();
            $table->json('symptoms')->after('is_first_appointment')->nullable();
            $table->text('symptoms_other')->after('symptoms')->nullable();
            $table->json('found_via')->after('symptoms_other')->nullable();
            $table->boolean('consent_updates')->after('found_via')->default(false);
            $table->string('guardian_gender')->after('consent_updates')->nullable();
            $table->string('guardian_name')->after('guardian_gender')->nullable();
            $table->string('guardian_relationship')->after('guardian_name')->nullable();
            $table->string('guardian_phone')->after('guardian_relationship')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'phone_country', 'gender', 'language',
                'ethnic_origin', 'age', 'is_first_appointment', 'symptoms',
                'symptoms_other', 'found_via', 'consent_updates',
                'guardian_gender', 'guardian_name', 'guardian_relationship', 'guardian_phone',
            ]);
        });
    }
};
