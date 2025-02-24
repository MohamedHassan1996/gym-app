<?php

namespace App\Http\Requests\DocumentType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\SwitchDbConnection;

class UpdateDocumentTypeRequest extends FormRequest
{
    use SwitchDbConnection;
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

        $this->switchDatabase();

        return [
            'documentTypeId' => 'required',
            'documentName' => "unique:tenant.document_types,name,{$this->documentTypeId}", // Specify the landlord connection explicitly
            'period' => 'required',
            'periodType' => 'required',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()
        ], 401));
    }

}
