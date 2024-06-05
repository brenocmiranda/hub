<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportsRqt extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'fileImport' => 'Arquivo de upload',
            'fieldsMandatory' => 'Nome da coluna',
            'fieldOptionalsName' => 'Nome do campo opcional',
            'fieldOptionalsName' => 'Nome da coluna opcional',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fileImport' => 'required|file',
            'fieldsMandatory' => 'required',
            'fieldOptionalsName' => 'nullable',
            'fieldOptionalsName' => 'nullable',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {   
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'file' => 'O campo :attribute deve possuir um arquivo.',
        ];    
    }
}
