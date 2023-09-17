<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDailySaleRecordRequest;
use App\Http\Requests\UpdateDailySaleRecordRequest;
use App\Http\Resources\BrandResource;
use App\Http\Resources\DailySaleResource;
use App\Http\Resources\DailyTotalResource;
use App\Http\Resources\MonthlySaleResource;
use App\Http\Resources\MonthlyTotalResource;
use App\Models\Brand;
use App\Models\DailySale;
use App\Models\DailySaleRecord;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Illuminate\Support\Carbon;

class FinanceController extends Controller
{

    public function daily()
    {
//Daily
        $query = request()->input('query');
        $dailySale = Voucher::when($query, function ($queryBuilder, $query) {
            return $queryBuilder->where('created_at', 'like', "%$query%");
        })
            ->when(request()->has('id'), function ($query) {
                $sortType = request()->id ?? 'asc';
                $query->orderBy("id", $sortType);
            })
            ->latest("id")->paginate(5)->withQueryString()->map(function ($col) {
                $quantity = VoucherRecord::where('voucher_id', $col->id);
                return collect([
                    'voucher' => $col->voucher_number,
                    'time' => $quantity->first()->time,
                    'item count' => $quantity->sum('quantity'),
                    'cash' => $col->total,
                    'tax' => $col->tax,
                    'total' => $col->net_total,
                ]);
            });

//DailyTotal
        $dailyTotal = DailySale::when($query, function ($queryBuilder, $query) {
            return $queryBuilder->where('created_at', 'like', "%$query%");
        })
            ->when(request()->has('id'), function ($query) {
                $sortType = request()->id ?? 'asc';
                $query->orderBy("id", $sortType);
            })
            ->latest("id")->paginate(1)->withQueryString()->filter(function ($item) {
                return $item['end'] != null;
            })->map(callback: function ($col) {

                return collect([
                    'Total Vouchers' => $col->vouchers,
                    'Total Cash' => $col->dailyCash,
                    'Total Tax' => $col->dailyTax,
                    'Total' => $col->dailyTotal,
                ]);
            });
        $collection = collect([
            'dailyTotal' => $dailyTotal[0],
            'dailySale' => $dailySale
        ]);
        return response()->json($collection);
    }

    public function monthly()
    {
//Monthly
        $query = request()->input('query');
        $monthlySale = DailySale::when($query, function ($queryBuilder, $query) {
            return $queryBuilder->where('created_at', 'like', "%$query%");
        })
            ->when(request()->has('id'), function ($query) {
                $sortType = request()->id ?? 'asc';
                $query->orderBy("id", $sortType);
            })
            ->latest("id")->paginate(5)->withQueryString()->map(callback: function ($col) {

                return collect([
                    'date' => $col->time,
                    'vouchers' => $col->vouchers,
                    'Cash' => $col->dailyCash,
                    'Tax' => $col->dailyTax,
                    'Total' => $col->dailyTotal,
                ]);
            });;

//Monthly Total
        $carbon = Carbon::now();
        $value = $query ?? $carbon->format('Y-m');
        $monthly = DailySale::whereDate('created_at', 'like', "%$value%")->get();
        $vouchers = $monthly->sum('vouchers');
        $cash = $monthly->sum('dailyCash');
        $tax = $monthly->sum('dailyTax');
        $total = $monthly->sum('dailyTotal');
        $totalDays = $monthly->last()->time;
        $monthCollection = ([
            'total days' => substr($totalDays, 0, 2),
            'total vouchers' => $vouchers,
            'total cash' => $cash,
            'total tax' => $tax,
            'total' => $total,
        ]);

        $collection = collect([
            'monthlyTotal' => $monthCollection,
            'monthlySale' => $monthlySale
        ]);
        return response()->json($collection);
    }

    public function yearly()
    {
//Yearly
        $query = request()->input('query');
        if (request()->has('query')) {
            $check = DailySale::whereYear('created_at', $query)->get();
            if ($check->isEmpty()) {
                return response()->json([
                    'message' => "There is no sale in this year $query"
                ]);
            }
        }
        $collectItem = collect(["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"])
            ->map(function ($col, $key) {
                $query = request()->input('query');
                $lastYear = DailySale::latest("id")->select('created_at')->first()->created_at;
                $value = $query ?? substr($lastYear, 0, 4);
                $finalValue = $value . "-" . $col;
                $monthly = DailySale::where('created_at', 'like', "%$finalValue%");
                $cash = $monthly->sum('dailyTax');
                $tax = $monthly->sum('dailyCash');
                $total = $monthly->sum('dailyTotal');
                return collect([
                    'year' => $value,
                    'month' => $col,
                    'cash' => $cash,
                    'tax' => $tax,
                    'total' => $total,
                ]);
            })
            ->filter(function ($item) {
                return $item['tax'] != 0;
            });

//Yearly Total
        $carbon = Carbon::now();
        $value = $query ?? $carbon->format('Y');
        $yearlySale = DailySale::whereDate('created_at', 'like', "%$value%")->get();

        $vouchers = $yearlySale->sum('vouchers');
        $cash = $yearlySale->sum('dailyCash');
        $tax = $yearlySale->sum('dailyTax');
        $total = $yearlySale->sum('dailyTotal');

        $totalMonth = $yearlySale->last()->created_at;
        $yearCollection = ([
            'total months' => substr($totalMonth, 5, 2),
            'total vouchers' => $vouchers,
            'total cash' => $cash,
            'total tax' => $tax,
            'total' => $total,
        ]);

        $collection = collect([
            'yearlyTotal' => $yearCollection,
            'yearlySale' => $collectItem
        ]);
        return response()->json($collection);
    }
}
