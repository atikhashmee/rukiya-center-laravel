<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ecommerce_categories = [
            [
                'category' => 'Apparel, Shoes, and Accessories',
                'subcategories' => [
                    'Clothing',
                    'Accessories',
                    'Shoes',
                    'Jewelry',
                ],
            ],
            [
                'category' => 'Home and Living',
                'subcategories' => [
                    'Home Decor',
                    'Furniture',
                    'Kitchen and Dining',
                    'Bedding and Bath',
                    'Outdoor and Garden',
                ],
            ],
            [
                'category' => 'Electronics and Gadgets',
                'subcategories' => [
                    'Consumer Electronics',
                    'Electronics Accessories',
                    'Audio and Music',
                    'Video Games',
                ],
            ],
            [
                'category' => 'Health and Beauty',
                'subcategories' => [
                    'Skincare and Makeup',
                    'Bath and Body',
                    'Hair Care',
                    'Personal Care',
                    'Health and Wellness',
                ],
            ],
            [
                'category' => 'Hobby, Craft, and Art Supplies',
                'subcategories' => [
                    'Craft Supplies and Tools',
                    'Digital Downloads',
                    'Art and Collectibles',
                    'Toys and Games',
                ],
            ],
            [
                'category' => 'Other Common Categories',
                'subcategories' => [
                    'Baby Products',
                    'Pet Supplies',
                    'Books, Movies, and Music',
                    'Food and Beverages',
                    'Automotive',
                ],
            ],
        ];

        foreach ($ecommerce_categories as $category) {
            $slug = Str::slug($category['category']);
            $productStored = ProductCategory::updateOrCreate(['slug' => $slug], [
                'slug' => $slug,
                'name' => $category['category'],
            ]);
        }

    }
}
