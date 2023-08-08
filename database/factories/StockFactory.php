<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stock>
 */
class StockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => rand(1, 6),
            'product_id' => rand(1, 20),
            'quantity' => fake()->numberBetween(1, 100),
            'more' => fake()->paragraph,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
