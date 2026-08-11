<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('services', 'appointment_type')) {
            Schema::table('services', function (Blueprint $table) {
                $table->enum('appointment_type', ['online', 'in_person', 'both'])
                    ->default('both')
                    ->after('required_form_fields')
                    ->comment('Which consultation formats this service supports');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('services', 'appointment_type')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropColumn('appointment_type');
            });
        }
    }
};
