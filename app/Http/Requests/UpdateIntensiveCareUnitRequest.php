<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIntensiveCareUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $icuId = $this->route('intensive_care_unit');

        return [
            'capacity' => ['required', 'integer' , 'gt:0'],
            'equipments' => ['required', 'exists:equipment,id'],
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('intensive_care_units')
                    ->ignore($icuId)
                    ->where(function ($query) {
                        return $query->where('hospital_id', $this->hospital_id);
                    }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'capacity.required' => 'The capacity field is required.',
            'capacity.integer' => 'The capacity must be an integer.',
            'capacity.gt' => 'The capacity must be greater than 0.',
            'equipments.required' => 'The equipments field is required.',
            'equipments.exists' => 'One or more selected equipments do not exist.',
            'code.required' => 'The code field is required.',
            'code.string' => 'The code must be a string.',
            'code.max' => 'The code may not be greater than 255 characters.',
            'code.unique' => 'The code has already been taken.',

        ];
    }
}
