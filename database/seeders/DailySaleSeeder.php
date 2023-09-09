<?php

namespace Database\Seeders;

use App\Models\DailySale;
use App\Models\DailySaleRecord;
use Carbon\CarbonPeriod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DailySaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subYear();

        $period = CarbonPeriod::create($startDate, $endDate);
        $DailyTotalSale = [];
        foreach ($period as $day) {
            $date = $day;
            $dailyVoucher = rand(1000, 9999);
//            $totalVoucher = $dailyVoucher->count('id');
//            $total = $dailyVoucher->sum('total');
//            $taxTotal = $dailyVoucher->sum('tax');
//            $netTotal = $dailyVoucher->sum('net_total');
            $DailyTotalSale[] = [
                "user_id" => rand(1, 100),
                "time" => $day->format('d M Y'),
                "vouchers" => rand(1, 50),
                'dailyTax' => rand(10, 800),
                'dailyCash' => rand(1000, 99999),
                'dailyTotal' => rand(1000, 99999),
                "created_at" => $day,
                "updated_at" => $day,
                "start" => $day,
                "end" => $day
            ];
        }
        DailySale::insert($DailyTotalSale);
    }
}
