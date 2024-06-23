<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
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
            'role'=>$this->user->role,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'history' => $this->history,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'phone' => $this->user->phone
        ];
    }
}
