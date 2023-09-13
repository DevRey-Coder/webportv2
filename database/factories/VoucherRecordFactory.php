<?php

namespace Database\Factories;

use Carbon\Carbon;
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
        $startDate = Carbon::create(2023, 9, 5);
        $endDate = Carbon::create(2023, 9, 11);  

        // Generate a random date within the specified range
        $randomDate = fake()->dateTimeBetween($startDate, $endDate);
        $items = [];
        for ($i = 0; $i < 5; $i++) {
            $items[] = [
                'name' => fake()->name(),
                'quantity' => fake()->randomDigit(),
            ];
        }
        return [
            "voucher_id" => rand(1, 5),
            "total_cost" => rand(1000, 20000),
            "items" => json_encode($items),
            "created_at" => $randomDate,
            "updated_at" => $randomDate
        ];
    }
}
