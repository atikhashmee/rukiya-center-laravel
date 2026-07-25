<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    private array $seedMeta = [
        'counseling' => ['name' => 'Counseling', 'icon' => 'heart-handshake', 'description' => 'Professional Islamic counselling services'],
        'rukiya' => ['name' => 'Rukiya', 'icon' => 'waves', 'description' => 'Prophetic healing and spiritual cleansing'],
        'istekhara' => ['name' => 'Istekhara', 'icon' => 'compass', 'description' => 'Istekhara guidance and definitive readings'],
    ];

    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('id_code')->constrained('service_categories')->restrictOnDelete();
        });

        $categoryValues = DB::table('services')->whereNotNull('category')->distinct()->pluck('category');

        foreach ($categoryValues as $value) {
            $meta = $this->seedMeta[$value] ?? [
                'name' => ucfirst(str_replace('_', ' ', $value)),
                'icon' => 'sparkles',
                'description' => null,
            ];

            $categoryId = DB::table('service_categories')->insertGetId([
                'name' => $meta['name'],
                'slug' => Str::slug($value),
                'description' => $meta['description'],
                'icon' => $meta['icon'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('services')->where('category', $value)->update(['category_id' => $categoryId]);
        }

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('category')->nullable()->after('id_code');
        });

        $services = DB::table('services')->join('service_categories', 'services.category_id', '=', 'service_categories.id')
            ->select('services.id', 'service_categories.slug')
            ->get();

        foreach ($services as $service) {
            DB::table('services')->where('id', $service->id)->update(['category' => $service->slug]);
        }

        Schema::table('services', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });
    }
};
