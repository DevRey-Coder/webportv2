<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'brand_id' => $this->brand_id,
            'sale_price' => $this->sale_price,
            'unit' => $this->unit,
            'more_information' => $this->more_information,
            'user_id' => $this->user_id,
            'photo' => $this->photo
        ];
    }
}
