<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RequestUpdate extends FormRequest
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
            'title' => 'nullable',
            'type_w' => 'nullable',
            'file1' => 'nullable|file|mimes:doc,docx,pdf,zip,png,jpg',
            'file2' => 'nullable|file|mimes:doc,docx,pdf,zip,png,jpg',
            'file3' => 'nullable|file|mimes:doc,docx,pdf,zip,png,jpg',
            'file4' => 'nullable|file|mimes:doc,docx,pdf,zip,png,jpg',
            'file5' => 'nullable|file|mimes:doc,docx,pdf,zip,png,jpg',
            'file6' => 'nullable|file|mimes:doc,docx,pdf,zip,png,jpg',
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
            'type_f' => 'nullable',
            'places' => 'nullable',
            'history' => 'nullable',
            'activity' => 'nullable',
            'is_knowledge' => 'nullable',
            'confirmation' => 'nullable|date',
            'expiration' => 'nullable|date',
            'area' => 'nullable'
        ];
    }
}
