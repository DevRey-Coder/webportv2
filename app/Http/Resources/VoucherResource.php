<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "customer" => $this->customer,
            "phone" => $this->phone,
            "voucher_number" => $this->voucher_number,
            "total" => $this->total,
            "tax" => $this->tax,
            "net_total" => $this->net_total,
            "user_id" => $this->user_id,
            "records" => $this->voucherRecords,
            "created_at" => $this->created_at->format("d m Y"),
            "updated_at" => $this->updated_at->format("d m Y"),
        ];
    }
}
