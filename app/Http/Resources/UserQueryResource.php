<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserQueryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request); 
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'gender' => $this->gender,
            'role' => $this->role,
            'user_id' => $this->user_id,
            'date_of_birth' => $this->date_of_birth,
            'user_photo' => $this->user_photo,
        ];
    }
}
