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
use Illuminate\Support\Carbon;

class DailySaleRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function daily()
    {
        $carbon = Carbon::now();
        $query = request()->input('query');
        $dailySale = DailySaleRecord::when($query, function ($queryBuilder, $query) {
            return $queryBuilder->where('created_at', 'like', "%$query%");
        })
            ->when(request()->has('id'), function ($query) {
                $sortType = request()->id ?? 'asc';
                $query->orderBy("id", $sortType);
            })
            ->latest("id")->paginate(5)->withQueryString();
        return DailySaleResource::collection($dailySale);
    }

    public function dailyTotal()
    {
        $query = request()->input('query');

        $dailyTotal = DailySale::when($query, function ($queryBuilder, $query) {
            return $queryBuilder->where('created_at', 'like', "%$query%");
        })
            ->when(request()->has('id'), function ($query) {
                $sortType = request()->id ?? 'asc';
                $query->orderBy("id", $sortType);
            })
            ->latest("id")->paginate(5)->withQueryString();

        return DailyTotalResource::collection($dailyTotal);
    }

    public function monthly()
    {
        $query = request()->input('query');
        $dailySale = DailySale::when($query, function ($queryBuilder, $query) {
            return $queryBuilder->where('created_at', 'like', "%$query%");
        })
            ->when(request()->has('id'), function ($query) {
                $sortType = request()->id ?? 'asc';
                $query->orderBy("id", $sortType);
            })
            ->latest("id")->paginate(5)->withQueryString();
        return MonthlySaleResource::collection($dailySale);

//$forStore = DailySale::where('cr)

    }

    public function monthlyTotal()
    {
        $query = request()->input('query');
        $carbon = Carbon::now();
        $value = $query ?? $carbon->format('Y-m');
        $monthlySale = DailySale::whereDate('created_at', 'like', "%$value%")->get();


        $vouchers = $monthlySale->sum('vouchers');
        $cash = $monthlySale->sum('dailyCash');
        $tax = $monthlySale->sum('dailyTax');
        $total = $monthlySale->sum('dailyTotal');
        $totalDays = $monthlySale->last()->time;

        return response()->json([
            'total days' => substr($totalDays, 0, 2),
            'total vouchers' => $vouchers,
            'total cash' => $cash,
            'total tax' => $tax,
            'total' => $total,
        ]);
    }

    public function yearly()
    {
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
        return response()->json($collectItem);
    }

    public function yearlyTotal()
    {
        $query = request()->input('query');
        $carbon = Carbon::now();
        $value = $query ?? $carbon->format('Y');
        $yearlySale = DailySale::whereDate('created_at', 'like', "%$value%")->get();

        $vouchers = $yearlySale->sum('vouchers');
        $cash = $yearlySale->sum('dailyCash');
        $tax = $yearlySale->sum('dailyTax');
        $total = $yearlySale->sum('dailyTotal');

        $totalMonth = $yearlySale->last()->created_at;

        return response()->json([
            'total months' => substr($totalMonth, 5, 2),
            'total vouchers' => $vouchers,
            'total cash' => $cash,
            'total tax' => $tax,
            'total' => $total,
        ]);
    }


}
