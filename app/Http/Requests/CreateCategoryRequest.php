<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para validação de criação de categoria
 * 
 * Responsável por validar dados de entrada antes que cheguem
 * aos controllers e use cases, garantindo integridade dos dados.
 * 
 * @package App\Http\Requests
 */
class CreateCategoryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:1',
                'regex:/^[^\s].*[^\s]$|^[^\s]$/' // Não pode começar ou terminar com espaços
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome da categoria é obrigatório.',
            'name.string' => 'O nome da categoria deve ser um texto.',
            'name.max' => 'O nome da categoria não pode ter mais de 255 caracteres.',
            'name.min' => 'O nome da categoria deve ter pelo menos 1 caractere.',
            'name.regex' => 'O nome da categoria não pode começar ou terminar com espaços.',
            'description.string' => 'A descrição deve ser um texto.',
            'description.max' => 'A descrição não pode ter mais de 1000 caracteres.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nome da categoria',
            'description' => 'descrição'
        ];
    }
}
