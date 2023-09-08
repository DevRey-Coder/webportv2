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
//        $query = request()->input('query');
//        $carbon = Carbon::now();
//        $value = $query ?? $carbon->format('Y-m');
//        $months = DailySale::where('created_at', 'like', "%$value%")->get();
//        foreach ($months as $month) {
//            $vouchers = $month->sum('vouchers');
//            $cash = $month->sum('dailyCash');
//            $tax = $month->sum('dailytax');
//            $total = $month->sum('dailyTotal');
//        return response()->json([
//        'vouchers' => $vouchers,
//        'cash' => $cash,
//        'tax' => $tax,
//        'total' => $total,
//        ]);
        $carbon = Carbon::now()->format('Y');
        $query = request()->input('queryY');
        $querys = request()->input('queryYM');
        $query1 = request()->input('queryM');

        $dailyCreated = DailySale::first()->created_at;
        $lastMonth = substr($dailyCreated, 0, 7);
        $lastYear = substr($dailyCreated, 0, 4);
        $month = substr($dailyCreated, 5, 2);
        $value = $query ?? $lastYear;
        $a = DailySale::where('created_at', 'like', "%$value%")->get();
        $value1 = $querys ?? $month;
        $start = $querys ?? $lastMonth;
        $end = $start . "-13";

        $yearly = $a->whereBetween('created_at', [$lastMonth, $end])->all();


    foreach($yearly as $year)
         {
//            $collect = collect(["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"]);
//            $collectItem = $collect->map(function (int $col) {
//                return $col;
//            });
             $b = $year->where('created_at','like', "%$start%")->get();

        };
        $d = $b->pull(dailyTax)->get();
//        return response()->json($c->all());
//$type = gettype($c);
        return response()->json($d);
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
