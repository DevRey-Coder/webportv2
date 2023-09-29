<?php

namespace Database\Seeders;

use App\Models\DailySale;
use App\Models\DailySaleRecord;
use App\Models\Voucher;
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
        $startDate = Carbon::create(2022, 7, 1);

//        $startDate = Carbon::now()->subYear(2);

        $period = CarbonPeriod::create($startDate, $endDate);
        $DailyTotalSale = [];
        foreach ($period as $day) {
            $date = $day;


            $dailyVoucher = Voucher::WhereDate('created_at', $date)->get();
            $totalVoucher = $dailyVoucher->count('id');
            $totalActualPrice = $dailyVoucher->sum('total_actual_price');
            $total = $dailyVoucher->sum('total');
            $taxTotal = $dailyVoucher->sum('tax');
            $netTotal = $dailyVoucher->sum('net_total');

            $DailyTotalSale[] = [
                'user_id' => rand(1, 5),
                "time" => $day->format('d M Y'),
                "vouchers" => $totalVoucher,
                "dailyActualTotal" => $totalActualPrice,
                'dailyTax' => $taxTotal,
                'dailyCash' => $total,
                'dailyTotal' => $netTotal,
                "created_at" => $day,
                "updated_at" => $day,
                "start" => $day,
                "end" => $day
            ];

//            $dailyVoucher = rand(1000, 9999);
//            $num_of_sales = rand(3, 6);
//            $tax = rand(10, 800);
//            $cash = rand(1000,99999);
//            for ($i = 0; $i < $num_of_sales; $i++) {
//                $DailyTotalSale[] = [
//                    "user_id" => rand(1, 100),
//                    "time" => $day->format('d M Y'),
//                    "vouchers" => rand(1, 50),
//                    'dailyTax' => $tax,
//                    'dailyCash' => $cash,
//                    'dailyTotal' => $tax + $cash,
//                    "created_at" => $day,
//                    "updated_at" => $day,
//                    "start" => $day,
//                    "end" => $day
//                ];
//            }

        }
        DailySale::insert($DailyTotalSale);
    }
}
