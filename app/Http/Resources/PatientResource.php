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
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'user_id'=>$this->user->id,
            'role'=>$this->user->role,
            'user_id'=>$this->user->id,
            'name'=>$this->user->name,
            'email'=>$this->user->email,
            'phone'=>$this->user->phone,
            'history'=>$this->history,
            'gender'=>$this->gender,
            'birth_date'=>$this->birth_date,
        ];
    }
}
