<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorAppointmentsResource extends JsonResource
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
            "doctor_id"=> $this->doctor_id,
            "kind_of_visit"=> $this->kind_of_visit,
            "day"=> $this->day,
            "date"=> $this->date,
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
