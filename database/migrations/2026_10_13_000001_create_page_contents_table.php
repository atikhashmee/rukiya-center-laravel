<?php

use App\Support\Region;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Text, rich text and images the client edits through the admin "Page content"
 * screens, stored per region so each country's site can say something different.
 * A row only exists once the value is changed; otherwise the template's own
 * default is used.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('region', 4)->default(Region::UK);
            $table->string('page', 100);       // theme page key, e.g. "index"
            $table->string('key', 150);        // field key, e.g. "hero_title"
            $table->longText('value')->nullable();
            $table->timestamps();

            $table->unique(['region', 'page', 'key']);
            $table->index(['region', 'page']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_contents');
    }
};
