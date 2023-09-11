<?php

namespace App\Http\Controllers;

use App\Http\Resources\DailyTotalResource;
use App\Models\DailySale;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportSaleController extends Controller
{
    public function dailyReport()
    {
        DB::enableQueryLog();
        $today = Carbon::createFromFormat('d M Y', '11 Sep 2023');

        $dailySales = DailySale::whereDate('time', '=', DB::raw('"11 Sep 2023"'))
        ->get();
        $queries = DB::getQueryLog();
        dd($queries);
        return $dailySales;
    }
}
