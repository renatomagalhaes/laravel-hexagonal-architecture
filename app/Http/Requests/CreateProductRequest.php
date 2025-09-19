<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para validação de criação de produto
 * 
 * Responsável por validar dados de entrada antes que cheguem
 * aos controllers e use cases, garantindo integridade dos dados.
 * 
 * @package App\Http\Requests
 */
class CreateProductRequest extends FormRequest
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
            'price' => [
                'required',
                'numeric',
                'min:0'
            ],
            'category_id' => [
                'required',
                'string',
                'min:1'
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
            'name.required' => 'O nome do produto é obrigatório.',
            'name.string' => 'O nome do produto deve ser um texto.',
            'name.max' => 'O nome do produto não pode ter mais de 255 caracteres.',
            'name.min' => 'O nome do produto deve ter pelo menos 1 caractere.',
            'name.regex' => 'O nome do produto não pode começar ou terminar com espaços.',
            'price.required' => 'O preço do produto é obrigatório.',
            'price.numeric' => 'O preço do produto deve ser um número.',
            'price.min' => 'O preço do produto não pode ser negativo.',
            'category_id.required' => 'O ID da categoria é obrigatório.',
            'category_id.string' => 'O ID da categoria deve ser um texto.',
            'category_id.min' => 'O ID da categoria deve ter pelo menos 1 caractere.',
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
            'name' => 'nome do produto',
            'price' => 'preço',
            'category_id' => 'ID da categoria',
            'description' => 'descrição'
        ];
    }
}
