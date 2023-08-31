<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDailySaleRequest;
use App\Http\Requests\UpdateDailySaleRequest;
use App\Http\Resources\DailySaleResource;
use App\Models\DailySale;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DailySaleController extends Controller
{


    public function index()
    {
        //
    }


    public function sessionOn(StoreDailySaleRequest $request)
    {
        $date = Carbon::now();
        $closedTime = $date->format('d') . "23";
        $dateNow = $date->format('d');
        $user = User::find(Auth::id());
        $user->session = true;
        $user->save();
        if (2 > substr($closedTime,0,2)){
            $session = DailySale::create([
                'user_id' => Auth::id(),
                'start' => $date->format('Y-m-d H:i:s'),
                'time' => $date->format('Y-m-d H:i:s'),
            ]);
            return new DailySaleResource($session);
        }
          return response()->json([
              "message" => "Your session has continued"
          ]);
    }

    public function sessionOff(DailySale $dailySale)
    {
        $date = Carbon::now();

        $user = User::find(Auth::id());
        $user->session = false;
        $user->save();
        $session = DailySale::orderBy('id', 'desc')->first();
        if (is_null($session->end)) {
            $session->end = $date->format('Y-m-d H:i:s');
            $session->save();
        }
        $session->end = $date->format('Y-m-d H:i:s');
        $session->update();


        return response()->json([
            "message" => "Your session has done"
        ]);    }


    public function destroy(DailySale $dailySale)
    {
        //
    }
}
