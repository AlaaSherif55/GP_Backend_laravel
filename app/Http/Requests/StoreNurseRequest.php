<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNurseRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,NULL,id,userable_type,App\Models\Doctor',
            'password' => 'required|string|min:6',
            'phone' => 'required|digits:11',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'university' => 'nullable|string|max:255',
            'qualifications' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'fees' => 'nullable|integer',
            'work_start' => 'nullable|string|max:255',
            'work_end' => 'nullable|string|max:255',
            'work_days' => 'nullable|string|max:255',

        ];
    }
}
