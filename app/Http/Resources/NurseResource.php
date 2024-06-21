<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NurseResource extends JsonResource
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
            'image'=>$this->image,
            'university'=>$this->university,
            'qualifications'=>$this->qualifications,
            'city'=>$this->city,
            'fees'=>$this->fees,
            'online'=>$this->online,
            'work_start'=>$this->work_start,
            'work_end'=>$this->work_end,
            'work_days'=>$this->work_days,
            'rate'=>$this->rate

        ];
    }
}
