<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * These columns already exist on some environments (added directly via SQL at
 * some point, never captured in a migration) - each addition is guarded so this
 * is safe to run whether the column is already there or not.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instructors', function (Blueprint $table) {
            if (! Schema::hasColumn('instructors', 'title')) {
                $table->string('title')->nullable()->after('name');
            }
            if (! Schema::hasColumn('instructors', 'experience')) {
                $table->string('experience', 100)->nullable()->after('title');
            }
            if (! Schema::hasColumn('instructors', 'location')) {
                $table->string('location', 100)->nullable()->after('experience');
            }
            if (! Schema::hasColumn('instructors', 'appointment_type')) {
                $table->string('appointment_type', 100)->nullable()->after('location');
            }
        });
    }

    public function down(): void
    {
        Schema::table('instructors', function (Blueprint $table) {
            $table->dropColumn(['title', 'experience', 'location', 'appointment_type']);
        });
    }
};
