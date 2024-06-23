<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HospitalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'address' => $this->address,
            'userId' => $this->user->id,
            'user' => $this->whenLoaded('user', function () {
                return [  
                    'userId' => $this->user->id,
                    'name' => $this->user->name,
                    'phone' => $this->user->phone,
                    'email' => $this->user->email,  
                    'role' => $this->user->role,  
                ];
            })
        ];
    }
}
