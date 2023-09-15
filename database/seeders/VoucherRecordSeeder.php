<?php

namespace Database\Seeders;

use App\Models\DailySaleRecord;
use App\Models\VoucherRecord;
use Carbon\CarbonPeriod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class VoucherRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subYear();
        $period = CarbonPeriod::create($startDate, $endDate);
        $num = 0;
        $DailyTotalSale = [];
        foreach ($period as $day) {
            $date = $day;
            $dailyVoucher = rand(1, 100);
            $num_of_daily_sales = rand(3, 6);
            for ($i = 0; $i < $num_of_daily_sales; $i++) {
                $DailyTotalSale[] = [
                    "voucher_id" => $dailyVoucher,
                    "product_id" => rand(1,20),
                    "quantity" => rand(5, 50),
                    "price" => rand(1000, 99999),
                    "cost" => rand(1000, 99999),
                    "created_at" => $day,
                    "updated_at" => $day
                ];
            }
        }
        VoucherRecord::insert($DailyTotalSale);
    }

}
