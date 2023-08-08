<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VoucherRecord>
 */
class VoucherRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "voucher_id" => fake()->numberBetween(1000, 9999),
            "product_id" => fake()->numberBetween(100, 999),
            "quantity" => fake()->numberBetween(100, 999),
            "cost" => fake()->numberBetween(1000, 9999),
        ];
    }
}
