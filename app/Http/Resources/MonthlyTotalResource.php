<?php

namespace App\Http\Resources;

use App\Models\DailySale;
use App\Models\DailySaleRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonthlyTotalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        return parent::toArray($request);
        $query = request()->input('query');

        $carbon = Carbon::now()->format('Y-m');
        $value =   $query ?? $carbon;
        $totalDay = DailySaleRecord::whereDate('created_at','like',"%$value%")->get()->count('created_at');
return [
    'Total Days' => $totalDay,
    'Total Vouchers' => $this->vouchers,
    'Total Cash' => $this->dailyCash,
    'Total Tax' => $this->dailyTax,
    'Total' => $this->dailyTotal,
];
    }
}
