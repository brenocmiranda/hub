<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UsersRqt extends FormRequest
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
            'email' => 'email',
            'active' => 'status',
            'companie' => 'empresa',
            'roles' => 'função',
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
                'name' => 'required|min:3',
                'email' => 'required|email|unique:users,email,'.$this->segment(3),
                'active' => 'required|boolean',
                'companie' => 'required|integer',
                'roles' => 'required|integer',
            ];
        } else {
            return [
                'name' => 'required|min:3',
                'email' => 'required|email|unique:users,email,'.$this->segment(3),
                'active' => 'required|boolean',
                'roles' => 'required|integer',
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
            'integer' => 'O campo :attribute só aceita valores inteiros.',
        ];   
    }
}
