<?php

namespace App\Http\Controllers;

use App\Models\DailySale;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Ramsey\Collection\Collection;

class OverviewController extends Controller
{
    public function overview()
    {
        $totalStocks = Stock::sum('quantity');
        $totalStaff = User::where('role', '=', 'staff')->count();


//Yearly Sales
        $start = substr(DailySale::select('time')->find(1), 16, 4);
        $end = Carbon::now()->format('Y');
        $collection1 = \Illuminate\Support\Collection::make();

        for ($i = $start; $i <= $end; $i++) {
            $collection1->push($i);
        }

        $yearly = $collection1->map(function ($col) {
            $item = DailySale::whereYear('created_at', 'like', "%$col%");
            $totalIncome = $item->sum('dailyTotal');
            $totalExpense = $item->sum('dailyActualTotal');


            return collect([
                'Yearly Sales' => collect([$col => $totalIncome]),
                'Total Income' => $totalIncome,
                'Total Expense' => $totalExpense,
            ]);
        });


//Weekly Sales
        $weeklyItem = \Illuminate\Support\Collection::make();

        // Get today's date

        // Check if a query parameter is present

        // Calculate the start and end dates for the week
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // Query for daily totals within the specified date range
        $dailyTotal = DailySale::whereBetween('created_at', [$startDate, $endDate]);
        $collectionWeek = collect([
            'Weekly Sales' => $dailyTotal->pluck('dailyTotal', 'time'),
            'Total Income' => $dailyTotal->sum('dailyTotal'),
            'Total Expense' => $dailyTotal->sum('dailyActualTotal'),
        ]);
        $weekly = $weeklyItem->push($collectionWeek);

//Monthly Sales
        $monthly = collect(["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"])
            ->map(function ($col) {
                $date = Carbon::now()->format('Y');
                $finalValue = $date . "-" . $col;
                $monthly = DailySale::where('created_at', 'like', "%$finalValue%");
                $totalIncome = $monthly->sum('dailyTotal');
                $totalExpense = $monthly->sum('dailyActualTotal');
                return collect([
//                    'Monthly Sales' => $col . ' : ' . $totalIncome,
                    'Monthly Sales' => collect([$col => $totalIncome]),
                    'Total Income' => $totalIncome,
                    'Total Expense' => $totalExpense,
                ]);
            });

//Today Overview
        $carbon = Carbon::now()->format('Y-m-d');

        $getTodayOverview = Voucher::whereDate('created_at', $carbon);
        $todayTotalOverview = collect([
            'Total Vouchers' => $getTodayOverview->count('voucher_number'),
            'Total Cash' => $getTodayOverview->sum('total'),
            'Total Tax' => $getTodayOverview->sum('tax'),
            'Total' => $getTodayOverview->sum('net_total'),
        ]);

        $todayOverview = $getTodayOverview->paginate(5)->map(function ($col) {

            return collect([
                'Sale Person' => $col->user->name,
                'Voucher' => $col->voucher_number,
                'Time' => $col->voucherRecords->pluck('time')[0],
                'Cash' => $col->total,
                'Tax' => $col->tax,
                'Total' => $col->net_total,
            ]);
        });


        $query = request()->input('query');
        switch ($query) {
            case($query == 'Yearly'):
                $sale = $yearly;

                break;
            case($query == 'Monthly'):
                $sale = $monthly;
                break;
            case($query == 'Weekly'):
                $sale = $weekly;
                break;
            default:
                $sale = 'Something went wrong.';
        }

        $totalIncome = $sale->sum('Total Income');
        $totalExpense = $sale->sum('Total Expense');

        $collection = collect([
            'Total Stocks' => $totalStocks,
            'Total Staff' => $totalStaff,
            'Sales' => $sale->pluck($query . " Sales"),
            'Total Profit' => $totalIncome - $totalExpense,
            'Total Income' => $totalIncome,
            'Total Expense' => $totalExpense,
            'Today Sales Overview' => $todayOverview,
            'Today Total Overview' => $todayTotalOverview,

        ]);

        return $collection;
    }
}
