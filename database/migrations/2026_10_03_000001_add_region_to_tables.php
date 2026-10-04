<?php

use App\Support\Region;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * One codebase serves several countries. Everything that differs per country gets a
 * "region" column; existing rows become UK, which is what the site is today.
 */
return new class extends Migration
{
    /** Catalog content (region-scoped on public pages) plus records that are only tagged. */
    private array $tables = [
        'services', 'service_categories', 'instructors', 'products', 'product_categories',
        'blog_posts', 'themes', 'bookings', 'orders', 'customers',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (! Schema::hasTable($table) || Schema::hasColumn($table, 'region')) {
                continue;
            }

            Schema::table($table, function (Blueprint $t) {
                $t->string('region', 4)->default(Region::UK)->index();
            });
        }

        // Settings are per region, so the same key can hold a different value per country.
        if (Schema::hasTable('settings') && ! Schema::hasColumn('settings', 'region')) {
            Schema::table('settings', function (Blueprint $t) {
                $t->string('region', 4)->default(Region::UK)->after('key');
                $t->dropUnique('settings_key_unique');
                $t->unique(['key', 'region']);
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'region')) {
                Schema::table($table, fn (Blueprint $t) => $t->dropColumn('region'));
            }
        }

        if (Schema::hasTable('settings') && Schema::hasColumn('settings', 'region')) {
            Schema::table('settings', function (Blueprint $t) {
                $t->dropUnique(['key', 'region']);
                $t->dropColumn('region');
                $t->unique('key');
            });
        }
    }
};
