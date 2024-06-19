<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,NULL,id,userable_type,App\Models\Doctor',
            'password' => 'required|string|min:6',
            'phone' => 'required|digits:11',
            'history' => 'required|string',
            'gender' => 'required|in:m,f',
            'birth_date' => 'required|date',
        ];
    }
}
