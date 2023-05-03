<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Contracts\Validation\Validator;

class UpdateUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'unique:users,username,' . $this->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ];

        if ($this->method() === 'PATCH') {
            $rules['email'] = [
                'nullable', 'max:190', 'email',
                Rule::unique('users')->ignore($this->id)
            ];

            $rules['password'] = ['nullable|min:6|max:100'];
        }

        return $rules;
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
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
