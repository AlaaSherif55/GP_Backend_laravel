<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIntensiveCareApplicationRequest extends FormRequest
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
            'patient_name' => [
                'required',
                'string',
                'max:255',
            ],
            'patient_phone' => [
                'required',
                'string',
                'regex:/^(010|011|012|015)[0-9]{8}$/',
            ],
            'description' => [
                'required',
                'string',
                'max:255',
            ],
            'intensive_care_unit_id' => [
                'required',
                'exists:intensive_care_units,id',
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'patient_name.required' => 'The patient name is required.',
            'patient_name.string' => 'The patient name must be a string.',
            'patient_name.max' => 'The patient name may not be greater than 255 characters.',

            'patient_phone.required' => 'The patient phone number is required.',
            'patient_phone.string' => 'The patient phone number must be a string.',
            'patient_phone.regex' => 'The patient phone number must be a valid Egyptian mobile number.',

            'description.required' => 'The description is required.',
            'description.string' => 'The description must be a string.',
            'description.max' => 'The description may not be greater than 255 characters.',

            'intensive_care_unit_id.required' => 'The intensive care unit id is required.',
            'intensive_care_unit_id.exists' => 'The intensive care unit id does not exist.',
        ];
    }
}
