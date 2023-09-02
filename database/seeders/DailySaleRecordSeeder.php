<?php

namespace Database\Seeders;

use App\Models\DailySaleRecord;
use Carbon\CarbonPeriod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DailySaleRecordSeeder extends Seeder
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
            $dailyVoucher = rand(1000,9999);
//            $totalVoucher = $dailyVoucher->count('id');
//            $total = $dailyVoucher->sum('total');
//            $taxTotal = $dailyVoucher->sum('tax');
//            $netTotal = $dailyVoucher->sum('net_total');
            $DailyTotalSale[] = [
                "voucher_number" => $dailyVoucher,
                "cash" => rand(1000,9999),
                "total" => rand(10000,99999),
                "count" => rand(1,10),
                'tax'=>rand(100,999),
                'time'=> substr($day,0,10),
                "created_at" => $day,
                "updated_at" => $day
            ];
        }
        DailySaleRecord::insert($DailyTotalSale);
    }
}
