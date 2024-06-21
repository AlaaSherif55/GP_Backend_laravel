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
            'address' => $this->address,
            'user' => $this->whenLoaded('user', function () {
                return [    
                    'name' => $this->user->name,
                    'phone' => $this->user->phone,
                    'email' => $this->user->email,  
                ];
            })
        ];
    }
}
