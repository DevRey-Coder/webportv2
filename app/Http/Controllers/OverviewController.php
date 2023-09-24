<?php

namespace App\Http\Controllers;

use App\Models\DailySale;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OverviewController extends Controller
{
    public function overview()
    {
        $totalStocks = Stock::sum('quantity');
        $totalStaff = User::where('role', '=', 'staff')->count();

//Monthly Sales
        $monthlyItem = collect(["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"])
            ->map(function ($col) {
                $date = Carbon::now()->format('Y');
                $finalValue = $date . "-" . $col;
                $monthly = DailySale::where('created_at', 'like', "%$finalValue%");
                $totalIncome = $monthly->sum('dailyTotal');
                $totalExpense = $monthly->sum('dailyActualTotal');
                return collect([
                    'Monthly Sales' => $col . ' : ' . $totalIncome,
                    'Total Income' => $totalIncome,
                    'Total Expense' => $totalExpense,
                ]);
            });

        $totalIncome = $monthlyItem->sum('Total Income');
        $totalExpense = $monthlyItem->sum('Total Expense');

        $todaySales = Voucher::latest('id')->paginate('5')->map(function ($col) {

            return collect([
                'Sale Person' => $col->user->name,
                'Voucher' => $col->voucher_number,
                'Time' => $col->voucherRecords->pluck('time')[0],
                'Cash' => $col->total,
                'Tax' => $col->tax,
                'Total' => $col->net_total,
            ]);
        });


        $collection = collect([
            'Total Stocks' => $totalStocks,
            'Total Staff' => $totalStaff,
            'Monthly Sales' => $monthlyItem->pluck('Monthly Sales'),
            'Total Profit' => $totalIncome - $totalExpense,
            'Total Income' => $totalIncome,
            'Total Expense' => $totalExpense,
            'Today Sales Overview' => $todaySales

        ]);
        return $collection;
    }
}
