<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class FileFacilitiesUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'facilities_id' => 'required',
            'file1' => 'nullable|file|mimes:doc,docx,pdf,zip,png,jpg',
            'file2' => 'nullable|file|mimes:doc,docx,pdf,zip,png,jpg',
            'file3' => 'nullable|file|mimes:doc,docx,pdf,zip,png,jpg',
            'licenses' => 'nullable',
            'register_doc' => 'nullable',
            'signatory' => 'nullable',
            'knowledge' => 'nullable',
            'resume' => 'nullable',
            'loans' => 'nullable',
            'statements' => 'nullable',
            'balances' => 'nullable',
            'catalogs' => 'nullable',
            'insurances' => 'nullable',
            'invoices' => 'nullable',
            'bills' => 'nullable',
        ];
    }
}
