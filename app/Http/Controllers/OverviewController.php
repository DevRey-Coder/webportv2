<?php

namespace App\Http\Controllers;

use App\Models\DailySale;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OverviewController extends Controller
{
    public function overview()
    {
        $totalStocks = Stock::sum('quantity');
        $totalStaff = User::where('role', '=', 'staff')->count();


        $collectItem = collect(["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"])
            ->map(function ($col) {
                $date = Carbon::now()->format('Y');
                $finalValue = $date . "-" . $col;
                $monthly = DailySale::where('created_at', 'like', "%$finalValue%");
                $total = $monthly->sum('dailyTotal');
                return collect([
                    $col => $total,
                ]);
            });


//        $collection = collect([
//            'Total Stocks' => $totalStocks,
//            'Total Staff' => $totalStaff,
//        ]);
        return $collectItem;
    }
}
