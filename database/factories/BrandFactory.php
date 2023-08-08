<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'company' => fake()->company,
            'information' => fake()->text,
            'user_id' => rand(1, 6),
            'photo' => fake()->imageUrl,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
