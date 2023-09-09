<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailySaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        return parent::toArray($request);}
return [
    'voucher' => $this->voucher_number,
    'time' => $this->time,
    'item count' => $this->count,
    'cash' => $this->cash,
    'tax' => $this->tax,
    'total' => $this->total,

];

    }
}
