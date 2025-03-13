<?php

namespace App\Http\Requests\Usuario;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|email|max:100',
            'password' => 'required|string|min:8|max:30',
        ];
    }

    public function messages(): array
    {
        return [
            'email.email' => 'El email no es valido',
            'email.required' => 'El email es requerido',
            'email.max' => 'El maximo de caracteres del email es 100',
            'password.required' => 'El password es requerido',
            'password.min' => 'El password debe tener minimo 8 caracteres',
            'password.max' => 'El password debe tener maximo 30 caracteres',
        ];
    }
}
