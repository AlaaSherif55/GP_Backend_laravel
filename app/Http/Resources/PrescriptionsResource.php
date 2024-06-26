<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionsResource extends JsonResource
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
            'doctor_id'=>$this->doctor_id,
            'doctor_name'=>$this->doctor->user->name,
            'doctor_phone'=>$this->doctor->user->phone,
            'patient_id'=>$this->patient_id,
            'user_id'=>$this->patient->user->id,
            'prescription_image'=>$this->prescription_image,
            'prescription_description'=>$this->description,
            'patient_history'=>$this->patient->history,
            'patient_gender'=>$this->patient->gender,
            'patient_DOB'=>$this->patient->birth_date,
            'patient_name'=>$this->patient->user->name,
            'patient_email'=>$this->patient->user->email,
            'patient_phone'=>$this->patient->user->phone,
        ];
    }
    
}
