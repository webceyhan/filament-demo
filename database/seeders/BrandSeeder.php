<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {        
        \App\Models\Brand::factory()->create([
            'name' => 'Apple',
            'slug' => 'apple',
            'url' => 'https://www.apple.com',
            'description' => 'Apple Inc. is an American multinational technology company headquartered in Cupertino, California, that designs, develops, and sells consumer electronics, computer software, and online services.',
        ]);

        \App\Models\Brand::factory()->create([
            'name' => 'Samsung',
            'slug' => 'samsung',
            'url' => 'https://www.samsung.com',
            'description' => 'Samsung is a South Korean multinational conglomerate headquartered in Samsung Town, Seoul. It comprises numerous affiliated businesses, most of them united under the Samsung brand, and is the largest South Korean chaebol.',
        ]);
    }
}
