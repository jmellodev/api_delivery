<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|min:4|max:255',
            "slug" => 'unique:products,slug',
            'description' => 'required|min:6|max:255',
            'price' => 'required',
            'sale_price' => 'nullable',
        ];

        if ($this->method() === 'PATCH') {
            $rules = [
                'name' => 'nullable|min:4|max:100',
                "slug" => ['nullable|unique:products,slug', Rule::unique('products')->ignore($this->id)],
                'description' => ['nullable|max:255'],
                'price' => 'nullable',
                'sale_price' => 'nullable',
            ];
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Nome é obrigatório',
            'slug.unique' => 'Slug já existe',
            'description.required' => 'Descrição é obrigatório',
            'email.unique' => 'Email já cadastrado',
            'price.required' => 'Preço é obrigatório'
        ];
    }
}
