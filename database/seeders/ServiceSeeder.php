<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Service::factory()->count(10)->create();
        Service::factory()->counseling()->count(2)->create();
        Service::factory()->rukiya()->count(2)->create();
        Service::factory()->istekara()->count(2)->create();
    }
}
