<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
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
            'Address'=>$this->Address,
            'clinic_fees'=>$this->clinic_fees,
            'home_fees'=>$this->home_fees,
            'online'=>$this->online,
            'specialization'=>$this->specialization,
            'visit'=>$this->visit,
            'clinic_work_start'=>$this->clinic_work_start,
            'clinic_work_end'=>$this->clinic_work_end,
            'home_work_start'=>$this->home_work_start,
            'home_work_end'=>$this->home_work_end,
            'work_days'=>$this->work_days,
        ];
    }
}
