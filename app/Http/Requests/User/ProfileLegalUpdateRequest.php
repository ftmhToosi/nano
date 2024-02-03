<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class ProfileLegalUpdateRequest extends FormRequest
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
            'type_legal' => 'nullable',
            'place_registration' => 'nullable',
            'establishment' => 'nullable',
            'signed_right' => 'nullable',
            'initial_investment' => 'nullable',
            'fund' => 'nullable',
            'subject_activity' => 'nullable',
            'name_representative' => 'nullable',
            'landline_phone' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable',
            'site' => 'nullable',
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
