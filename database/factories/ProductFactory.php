<?php

namespace Database\Factories;

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
        $salePrice = fake()->randomFloat(2, 80, 800);
        $totalPrice = $salePrice * fake()->randomDigitNotNull;
        $unit = fake()->randomElement(['kg', 'pcs', 'box', 'set']);
        $moreInformation = fake()->paragraph;
        $photo = fake()->imageUrl;

        return [
            'name' => fake()->word,
            'brand_id' => rand(1, 20),
            'actual_price' => fake()->randomFloat(2, 100, 1000),
            'sale_price' => $salePrice,
            'total_price' =>  $totalPrice,
            'unit' => $unit,
            'more_information' => $moreInformation,
            'user_id' => rand(1, 20),
            'photo' => $photo,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
