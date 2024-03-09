<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiLeadsRqt extends FormRequest
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
            'name' => 'nome',
            'phone' => 'telefone',
            'email' => 'email',
            'origin' => 'origem',
            'building' => 'empreendimento',
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
            'name' => 'required_without:nome|min:3',
            'nome' => 'required_without:name|min:3',
            'phone' => 'required_without:telefone|min:3',
            'telefone' => 'required_without:phone|min:3',
            'email' => 'required|email',
            'building' => 'required_without:empreendimento|string|min:3',
            'empreendimento' => 'required_without:building|string|min:3',
            'utm_source' => 'nullable',
            'utm_medium' => 'nullable',
            'utm_campaign' => 'nullable',
            'utm_term' => 'nullable',
            'utm_content' => 'nullable',
            'gclid' => 'nullable',
            'fbclid' => 'nullable',
            'pp' => 'nullable',
            'com' => 'nullable',
            'url' => 'nullable|url:http,https',
            'message' => 'nullable',
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
            'required_without' => 'O campo :attribute é obrigatório.',
            'min' => 'O campo :attribute deve possuir no minimo :min caracteres',
            'unique' => 'O campo :attribute já foi cadastrado, tente novamente.',
            'numeric' => 'O campo :attribute só aceita valores númericos.',
            'boolean' => 'O campo :attribute só pode receber ativo ou desativado.',
            'integer' => 'O campo :attribute só aceita valores inteiros.',
        ];    
    }

    /**
     * Return erros in validation
     *
     * @return array<string, string>
     */
    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Erros de validação.',
            'data'      => $validator->errors()
        ]));
    }
}
