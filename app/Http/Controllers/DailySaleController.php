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
}
