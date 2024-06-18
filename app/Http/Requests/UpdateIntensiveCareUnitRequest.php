<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIntensiveCareUnitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'capacity' => ['required', 'integer'],
            'equipments' => ['required' , 'exists:equipment,id'],
        ];
    }
    public function messages(): array
    {
        return [
            'capacity.required' => 'The capacity field is required.',
            'capacity.integer' => 'The capacity must be an integer.',
            'equipments.required' => 'The equipments field is required.',
            'equipments.exists' => 'One or more selected equipments do not exist.',
        ];
    }

}
