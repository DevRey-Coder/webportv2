<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'address' => $this->address,
            'gender' => $this->gender,
            'role' =>$this->role,
            'user_id' => $this->user_id,
            'date_of_birth' => $this->date_of_birth,
            'user_photo' => $this->user_photo,
        ];
    }
}
