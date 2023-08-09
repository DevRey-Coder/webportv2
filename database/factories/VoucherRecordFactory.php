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
            "voucher_id" => rand(1, 5),
            "product_id" => rand(1, 20),
            "quantity" => rand(1, 100),
            'cost' => rand(1, 100)
        ];
    }
}
