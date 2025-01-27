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
            // Validate nome
            'name' => 'required_without_all:nome|string|min:3',
            'nome' => 'required_without_all:name|string|min:3',

            // Validate telefone
            'telefone' => 'required_without_all:celular,phoneNumber,phone|min:3',
            'celular' => 'required_without_all:telefone,phoneNumber,phone|min:3',
            'phoneNumber' => 'required_without_all:telefone,celular,phone|min:3',
            'phone' => 'required_without_all:telefone,celular,phoneNumber|min:3',

            // Validate email
            'email' => 'required|email',

            // Validate empreendimento
            'building' => 'required_without_all:empreendimento,originListingId,codigoDoAnunciante,idNavplat|min:3',
            'empreendimento' => 'required_without_all:building,originListingId,codigoDoAnunciante,idNavplat|min:3',
            'originListingId' => 'required_without_all:empreendimento,building,codigoDoAnunciante,idNavplat|min:3',
            'codigoDoAnunciante' => 'required_without_all:empreendimento,originListingId,building,idNavplat|min:3',
            'idNavplat' => 'required_without_all:empreendimento,originListingId,building,codigoDoAnunciante|min:3',

            // Validate origin
            'origin' => 'required_without_all:origem,leadOrigin|string|min:3',
            'origem' => 'required_without_all:origin,leadOrigin|string|min:3',
            'leadOrigin' => 'required_without_all:origin,origem|string|min:3',

            // Validate others (utm_source)
            'utm_source' => 'nullable|string',
            'plataforma' => 'nullable|string',
            'leadOrigin' => 'nullable|string',

            // Validate others (utm_campaign)
            'utm_campaign' => 'nullable|string',
            'campanha' => 'nullable|string',
            'codigoImobiliaria' => 'nullable|numeric',

            // Validate others (utm_medium)
            'utm_medium' => 'nullable|string',
            'nome_form' => 'nullable|string',
            'clientListingId' => 'nullable|string',
            'planoDePublicacao' => 'nullable|string',

            // Validate others (utm_content)
            'utm_content' => 'nullable|string',
            'ad_name' => 'nullable|string',

            // Validate others (utm_term)
            'utm_term' => 'nullable|string',
            'adset_name' => 'nullable|string',

            // Validate others (sobrenome)
            'sobrenome' => 'nullable|string',

            // Validate others (mensagem)
            'message' => 'nullable|string',
            'mensagem' => 'nullable|string',

            // Validate others (url)
            'url' => 'nullable|url:http,https',

            // Validate others (pp)
            'pp' => 'nullable|string',

            // Validate others (com)
            'com' => 'nullable|string',
            'comunicacao' => 'nullable|string',
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
        ], 412));
    }
}
