<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class FileFacilitiesRequest extends FormRequest
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
            'file1' => 'required|file|mimes:doc,docx,pdf,zip,png,jpg',
            'file2' => 'required|file|mimes:doc,docx,pdf,zip,png,jpg',
            'file3' => 'required|file|mimes:doc,docx,pdf,zip,png,jpg',
            'licenses' => 'required',
            'register_doc' => 'required',
            'signatory' => 'required',
            'knowledge' => 'required',
            'resume' => 'required',
            'loans' => 'required',
            'statements' => 'required',
            'balances' => 'required',
            'catalogs' => 'required',
            'insurances' => 'required',
            'invoices' => 'required',
            'bills' => 'required',
        ];
    }
}
