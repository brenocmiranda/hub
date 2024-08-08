<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class IntegrationsRqt extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return TRUE;
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
            'type' => 'tipo',
            'slug' => 'slug',
            'active' => 'status',
            'encoded' => 'http_query_build',
            'url' => 'URL',
            'user' => 'usuário',
            'password' => 'senha',
            'token' => 'token',
            'companie' => 'empresa',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {   
        if( Gate::check('access_komuh') ){
            return [
                'type' => 'required|min:3',
                'name' => 'required|min:3|unique:integrations,name,'.$this->segment(3),
                'slug' => 'required|min:3',
                'url' => 'required|min:3|url:http,https',
                'user' => 'min:3|nullable',
                'password' => 'min:3|nullable',
                'token' => 'min:3|nullable',
                'header' => 'min:3|nullable',
                'active' => 'required|boolean',
                'encoded' => 'required|boolean',
                'companie' => 'required|integer',
            ];
        } else {
            return [
                'type' => 'required|min:3',
                'name' => 'required|min:3|unique:integrations,name,'.$this->segment(3),
                'slug' => 'required|min:3',
                'url' => 'required|min:3|url:http,https',
                'user' => 'min:3|nullable',
                'password' => 'min:3|nullable',
                'token' => 'min:3|nullable',
                'header' => 'min:3|nullable',
                'active' => 'required|boolean',
                'encoded' => 'required|boolean',
            ];
        }
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
            'min' => 'O campo :attribute deve possuir no minimo :min caracteres',
            'unique' => 'O campo :attribute já foi cadastrado, tente novamente.',
            'numeric' => 'O campo :attribute só aceita valores númericos.',
            'boolean' => 'O campo :attribute só pode receber ativo ou desativado.',
            'url' => 'O campo :attribute é inválido.',
        ];   
    }
}
