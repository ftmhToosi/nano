<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class ProfileGenuineUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'image' => 'nullable|file|mimes:jpg,png|max:2048',
            'father_name' => 'nullable',
            'number_certificate' => 'nullable',
            'birth_day' => 'nullable',
            'place_issue' => 'nullable',
            'series_certificate' => 'nullable',
            'nationality' => 'nullable',
            'gender' => 'nullable',
            'marital' => 'nullable',
            'residential' => 'nullable',
            'study' => 'nullable',
            'education' => 'nullable',
            'job' => 'nullable',
            'address' => 'nullable',
            'postal_code' => 'nullable',
            'home_number' => 'nullable|min:11|max:11',
            'namabar' => 'nullable',
            'work_address' => 'nullable',
            'work_postal_code' => 'nullable',
            'work_phone' => 'nullable|min:11|max:11',
            'work_namabar' => 'nullable',
        ];
    }

    public function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json(
            [
                'success' => false,
                'message' => $validator->errors()
            ],
            422)
        );
    }
}
