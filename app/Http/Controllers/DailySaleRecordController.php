<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDailySaleRecordRequest;
use App\Http\Requests\UpdateDailySaleRecordRequest;
use App\Http\Resources\BrandResource;
use App\Http\Resources\DailySaleResource;
use App\Models\Brand;
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
            return $queryBuilder->where('time', 'like', "%$query%");
            // ->orWhere('brand', 'like', "%$query%");
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
        $carbon = Carbon::now();
        $date = $carbon->format('Y-m-d');
        $total = DailySaleRecord::where('time', '2023-09-01')->get();

        $dailyTotal = [];
       $dailyTotal[] = $total->sum('cash');
       $dailyTotal[] = $total->sum('total');
        return response()->json($dailyTotal);

    }

    public function monthly(StoreDailySaleRecordRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DailySaleRecord $dailySaleRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDailySaleRecordRequest $request, DailySaleRecord $dailySaleRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailySaleRecord $dailySaleRecord)
    {
        //
    }
}
