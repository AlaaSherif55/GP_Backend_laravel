<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\IntensiveCareUnitResource;
class IntensiveCareApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'patient_name' => $this->patient_name,
            'patient_phone' => $this->patient_phone,
            'status' => $this->status,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'intensive_care_unit' => new IntensiveCareUnitResource($this->whenLoaded('intensiveCareUnit'))
        ];
    }
}
