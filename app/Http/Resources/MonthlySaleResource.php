<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonthlySaleResource extends JsonResource
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
            'date' => $this->time,
            'vouchers' => $this->vouchers,
            'Cash' => $this->dailyCash,
            'Tax' => $this->dailyTax,
            'Total' => $this->dailyTotal,
        ];
    }
}
