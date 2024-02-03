<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class RequestsRequest extends FormRequest
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

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
            'user_id' => 'required',
            'type' => 'required',
            'title' => 'required',
            'type_w' => 'required_if:type,warranty',
            'file1' => 'required_if:type,warranty|file|mimes:doc,docx,pdf,zip,png,jpg',
            'file2' => 'required_if:type,warranty|file|mimes:doc,docx,pdf,zip,png,jpg',
            'file3' => 'required_if:type,warranty|file|mimes:doc,docx,pdf,zip,png,jpg',
            'file4' => 'required_if:type,warranty|file|mimes:doc,docx,pdf,zip,png,jpg',
            'file5' => 'required_if:type,warranty|file|mimes:doc,docx,pdf,zip,png,jpg',
            'file6' => 'required_if:type,warranty|file|mimes:doc,docx,pdf,zip,png,jpg',
            'licenses' => 'required_if:type,warranty|array|max:3',
            'licenses.*' => 'file|max:5000',
            'register_doc' => 'required_if:type,warranty|array|max:3',
            'register_doc.*' => 'file|max:5000',
            'signatory' => 'required_if:type,warranty|array|max:3',
            'signatory.*' => 'file|max:5000',
            'knowledge' => 'required_if:type,warranty|array|max:3',
            'knowledge.*' => 'file|max:5000',
            'resume' => 'required_if:type,warranty|array|max:3',
            'resume.*' => 'file|max:5000',
            'loans' => 'required_if:type,warranty|array|max:3',
            'loans.*' => 'file|max:5000',
            'statements' => 'required_if:type,warranty|array|max:3',
            'statements.*' => 'file|max:5000',
            'balances' => 'required_if:type,warranty|array|max:3',
            'balances.*' => 'file|max:5000',
            'catalogs' => 'required_if:type,warranty|array|max:3',
            'catalogs.*' => 'file|max:5000',
            'insurances' => 'required_if:type,warranty|array|max:3',
            'insurances.*' => 'file|max:5000',
            'invoices' => 'required_if:type,warranty|array|max:3',
            'invoices.*' => 'file|max:5000',
            'bills' => 'required_if:type,warranty|array|max:3',
            'bills.*' => 'file|max:5000',
            'type_f' => 'required_if:type,facilities',
            'places' => 'required_if:type,facilities',
            'history' => 'required_if:type,facilities',
            'activity' => 'required_if:type,facilities',
            'is_knowledge' => 'required_if:type,facilities',
            'confirmation' => 'nullable|date',
            'expiration' => 'nullable|date',
            'area' => 'nullable'
        ];
    }

    public function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
    {
        $id = $this->id;
        throw new HttpResponseException(response()->json(
            [
                'id' => $id,
                'success' => false,
                'message' => $validator->errors()
            ],
            422)
        );
    }
}
