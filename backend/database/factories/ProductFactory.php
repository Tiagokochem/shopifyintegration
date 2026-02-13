<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'shopify_id' => (string) $this->faker->unique()->randomNumber(8),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'vendor' => $this->faker->company(),
            'product_type' => $this->faker->word(),
            'status' => $this->faker->randomElement(['active', 'draft', 'archived']),
            'synced_at' => now(),
        ];
    }
}
