<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voucher>
 */
class VoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "customer" => fake()->name(),
            "voucher_number" => fake()->numberBetween(1000, 9999),
            "total" => fake()->numberBetween(1000, 9999),
            "tax" => fake()->numberBetween(100, 999),
            "net_total" => fake()->numberBetween(1000, 9999),
            "user_id" => fake()->numberBetween(1, 10)];

    }
}
