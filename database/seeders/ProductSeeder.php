<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    const SAMPLE_MAP = [
        'apple' => [
            'phone' => [
                'iPhone 12',
                'iPhone 12 Pro',
                'iPhone SE',
            ],
            'tablet' => [
                'iPad Pro',
                'iPad Air',
                'iPad',
            ],
            'laptop' => [
                'MacBook Pro',
                'MacBook Air',
                'MacBook',
            ],
        ],
        'samsung' => [
            'phone' => [
                'Galaxy S21',
                'Galaxy S21 Ultra',                
            ],
            'tablet' => [                
                'Galaxy Tab S7',
                'Galaxy Tab S6 Lite',
            ],
            'laptop' => [
                'Galaxy Book Flex',
                'Galaxy Book Ion',
                'Galaxy Book S',
            ],
        ],
    ];


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // populate products by brand, category, and name

        foreach (self::SAMPLE_MAP as $brandSlug => $categories) {
            // get brand
            $brand = \App\Models\Brand::where('slug', $brandSlug)->first();

            foreach ($categories as $categorySlug => $products) {
                // get category
                $category = \App\Models\Category::where('slug', $categorySlug)->first();

                foreach ($products as $productName) {

                    $product = \App\Models\Product::factory()->create([
                        'name' => $productName,
                        'brand_id' => $brand->id,
                        'slug' => \Illuminate\Support\Str::slug($productName),
                        'sku' => \Illuminate\Support\Str::slug($productName),
                        'image_url' => 'https://via.placeholder.com/300x300',
                        'description' => $productName . '. Description.',
                        // add random timestamp from beginning of this year
                        'created_at' => now()->startOfYear()->addDays(rand(0, 365)),
                    ]);

                    $product->categories()->attach($category);
                }
            }
        }
    }
}
