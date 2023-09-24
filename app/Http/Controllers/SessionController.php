<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDailySaleRequest;
use App\Http\Requests\UpdateDailySaleRequest;
use App\Http\Resources\SessionResource;
use App\Models\DailySale;
use App\Models\DailySaleRecord;
use App\Models\User;
use App\Models\VoucherRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isEmpty;

class SessionController extends Controller
{


    public function sessionOn(StoreDailySaleRequest $request)
    {
        $date = Carbon::now();
        $checkSession = DailySale::whereDate('created_at', $date->format('Y-m-d'))->get();
        $user = User::find(Auth::id());
        $user->session = true;
        $user->save();

        if ($checkSession->isEmpty()) {
            $session = DailySale::create([
                'user_id' => Auth::id(),
                'start' => $date->format('Y-m-d H:i:s'),
            ]);
            return new SessionResource($session);
        }

        return response()->json([
            "message" => "Your session has continued",
        ]);
    }

    public function sessionOff(DailySale $dailySale)
    {
        $date = Carbon::now();
        $user = User::find(Auth::id());
        $user->session = false;
        $user->save();
        $total = VoucherRecord::whereDate('created_at', $date->format('Y-m-d'))->get();
        $session = DailySale::orderBy('id', 'desc')->first();
        if (is_null($session->end)) {
            $session->end = $date->format('Y-m-d H:i:s');
            $session->save();
        }
        $session->time = $date->format('d M Y');
        $session->end = $date->format('Y-m-d H:i:s');
        $session->vouchers = $total->count('voucher_number');
        $session->dailyCash = $total->sum('cost');
        $session->dailyTax = $total->sum('tax');
        $session->dailyTotal = $total->sum('price');
        $session->dailyActualTotal = $total->sum('actual_total');
        $session->update();


        return response()->json([
            "message" => "Your session has done"
        ]);
    }


    public function destroy(DailySale $dailySale)
    {
        //
    }
}
