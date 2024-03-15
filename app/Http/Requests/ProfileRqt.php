<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRqt extends FormRequest
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
            'src' => 'imagem de perfil',
            'nome' => 'email',
            'email' => 'email',
            'password' => 'senha',
            'password_confirmation' => 'confirmação de senha',
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
            'src' => 'image|nullable',
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'nullable',
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
            'image' => 'O campo :attribute deve ser preenchido com uma imagem.',
            'email' => 'O campo :attribute deve possuir um e-mail válido',
            'required' => 'O campo :attribute é obrigatório.',
            'min' => 'O campo :attribute deve possuir no minimo :min caracteres',
            'unique' => 'O campo :attribute já foi cadastrado, tente novamente.',
            'numeric' => 'O campo :attribute só aceita valores númericos.',
            'boolean' => 'O campo :attribute só pode receber ativo ou desativado.',
            'exists' => 'O :attribute não foi encontrado.', 
            'same' => 'O campo :attribute e :other estão diferentes.',
        ];   
    }
}
