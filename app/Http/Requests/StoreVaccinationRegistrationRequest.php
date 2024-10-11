<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVaccinationRegistrationRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:vaccination_registrations'],
            'nid' => ['required', 'integer', 'min:10', 'unique:vaccination_registrations'],
            'mobile_number' => ['required', 'size:11', 'regex:/^01[0-9]{9}$/'],
            'vaccine_center_id' => ['required', 'integer', 'exists:vaccine_centers,id'],
        ];
    }
}
