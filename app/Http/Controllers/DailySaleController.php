<?php

namespace App\Http\Controllers;

use App\Models\DailySale;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailySaleController extends Controller
{
    public function dailySale()
    {
        $dailySales = DailySale::whereDate('date', Carbon::today())->get();

        return response()->json([
            'success' => true,
            'data' => $dailySales,
        ]);
    }

    public function monthlySale($month = null)
    {
        $month = $month ?? now()->month;
        $dailySales = DailySale::whereMonth('date', $month)->get();

        $total = 0;
        foreach ($dailySales as $dailySale) {
            $total += $dailySale->net_total;
        }

        return $total;
    }

    public function yearlySale($year = null)
    {
        $year = $year ?? now()->year;
        $dailySales = DailySale::whereYear('date', $year)->get();

        $total = 0;
        foreach ($dailySales as $dailySale) {
            $total += $dailySale->net_total;
        }

        return $total;
    }

    public function customSale(Request $request)
    {
        $date = $request->input('date');

        $dailySales = DailySale::whereDate('date', $date)->get();

        $total = 0;
        foreach ($dailySales as $dailySale) {
            $total += $dailySale->net_total;
        }

        return $total;
    }
}
