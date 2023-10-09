<?php

namespace Database\Factories;

use App\Enums\ProductType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'slug' => $this->faker->slug,
            'sku' => $this->faker->unique()->numberBetween(100000, 999999),
            'image_url' => $this->faker->imageUrl(),
            'description' => $this->faker->paragraph,
            'quantity' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->randomFloat(2, 100, 1000),
            'is_visible' => $this->faker->boolean,
            'is_featured' => $this->faker->boolean,
            'type' => $this->faker->randomElement(ProductType::values()),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
