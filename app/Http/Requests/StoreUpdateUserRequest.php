<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateUserRequest extends FormRequest
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
            'name' => 'required|min:4|max:100',
            "username" => 'required|unique:users,username',
            'email' => 'required|max:190|email|unique:users,email',
            'password' => 'required|min:6|max:100',
            'c_password' => 'required|same:password',
        ];

        if ($this->method() === 'PATCH') {
            $rules = [
                'name' => 'nullable|min:4|max:100',
                "username" => 'nullable|unique:users,username',
                'email' => ['nullable|max:190', 'email', 'unique:users', 'email', Rule::unique('users')->ignore($this->id)],
                'password' => 'nullable|min:6|max:100',
                'c_password' => 'nullable|same:password',
            ];
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Nome é obrigatório',
            'username.required' => 'Nome de usuário obrigatório',
            'username.unique' => 'Nome de usuário já existe',
            'email.required' => 'Email é obrigatório',
            'email.unique' => 'Email já cadastrado',
            'password.required' => 'Senha é obrigatório',
            'c_password.required' => 'Confirmação de senha é obrigatório.',
            'c_password.same' => 'Senha e confirmar senha não combinam.'
        ];
    }
}
