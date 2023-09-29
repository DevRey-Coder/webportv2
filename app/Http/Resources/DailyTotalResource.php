<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailyTotalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //        return parent::toArray($request);
        return [
            'Total Vouchers' => $this->vouchers,
            'Total Cash' => $this->dailyCash,
            'Total Tax' => $this->dailyTax,
            'Total' => $this->dailyTotal,
            'Created_at' => $this->created_at
        ];
    }
}
