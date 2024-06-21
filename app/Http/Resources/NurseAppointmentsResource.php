<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NurseAppointmentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return[
            "id"=> $this->id,
            "patient_id"=>$this->patient_id,
            "nurse_id"=> $this->nurse_id,
            "day"=> $this->day,
            "status"=>$this->status,
            "notes"=> $this->notes,
            "patient_history"=>$this->patient->history,
            "patient_gender"=>$this->patient->gender,
            "patient_DOB"=>$this->patient->birth_date,
            "patient_name"=>$this->patient->user->name,
            "patient_phone"=>$this->patient->user->phone,
        ];

    }
}
