<?php

namespace App\Http\Resources;

use App\Models\Voucher;
use App\Models\VoucherRecord;
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

$quantity = VoucherRecord::where('voucher_id',$this->id);

        return [
            'voucher' => $this->voucher_number,
            'time' => $quantity->first()->time,
            'item count' => $quantity->sum('quantity'),
            'cash' => $this->total,
            'tax' => $this->tax,
            'total' => $this->net_total,

        ];

    }
}
