<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class ExpertRequest extends FormRequest
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
            'name' => 'required|string',
            'family' => 'required|string',
            'national_code' => 'required|string|min:10|max:10|unique:users',
            'phone' => 'required|string|max:11|min:11|unique:users',
            'email' => 'nullable|string|email|max:255|unique:users',
            'password' => 'required|string|min:3|confirmed',
            // 'password' => [
            //     'required',
            //     'string',
            //     'min:10',             // must be at least 10 characters in length
            //     'regex:/[a-z]/',      // must contain at least one lowercase letter
            //     'regex:/[A-Z]/',      // must contain at least one uppercase letter
            //     'regex:/[0-9]/',      // must contain at least one digit
            //     'regex:/[@$!%*#?&]/', // must contain a special character
            // ],
            'image' => 'required|file|mimes:png,jpg|max:2048',
            'father_name' => 'nullable',
            'number_certificate' => 'required',
            'birth_day' => 'required',
            'place_issue' => 'nullable',
            'series_certificate' => 'nullable',
            'nationality' => 'nullable',
            'gender' => 'required',
            'marital' => 'nullable',
            'residential' => 'nullable',
            'study' => 'nullable',
            'education' => 'nullable',
            'job' => 'nullable',
            'address' => 'required',
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
