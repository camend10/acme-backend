<?php

namespace App\Http\Requests\Usuario;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UsuarioRequest extends FormRequest
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
        $userId = $this->route('id');
        // Log::info('Datos finales antes de insertar:', [$this->method()]);
        return match ($userId ? 'PUT' : $this->method()) {
            'POST' => [
                'name' => [
                    'required',
                    'string',
                    'max:50'
                ],
                'email' => [
                    'required',
                    'email',
                    'max:100',
                    Rule::unique('users')
                ],
                'password' => 'required|string|min:8|max:30',
                // 'password_confirmation' => 'required_with:password',
                'role_id' => 'required',
                'direccion' => 'string|nullable',
                'telefono' => 'required',
                'estado' => 'string|nullable',
            ],
            'PUT' =>  [
                'name' => [
                    'required',
                    'string',
                    'max:50'
                ],
                'email' => [
                    'required',
                    'email',
                    'max:100',
                    Rule::unique('users')->ignore($this->id)
                ],
                'role_id' => 'required',
                'direccion' => 'string|nullable',
                'telefono' => 'required',
                'estado' => 'string|nullable',
            ]
        };
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.string' => 'El nombre debe ser una cadena de caracteres',
            'name.max' => 'El maximo de caracteres del nombre es 50',
            'name.unique' => 'Ya existe un usuario con este nombre y correo',
            'email.email' => 'Correo no valido',
            'email.required' => 'El correo es obligatorio',
            'email.max' => 'El maximo de caracteres del email es 100',
            'email.unique' => 'Ya existe un usuario con este correo y nombre',
            'password.required' => 'La clave es obligatoria',
            'password.confirmed' => 'Las claves no coinciden',
            'password_confirmation.required_with' => 'Debes confirmar la clave',
            'password.string' => 'La clave debe ser una cadena de caracteres',
            'password.max' => 'El maximo de caracteres de la clave es 30',
            'password.min' => 'El minimo de caracteres de la clave es 8',
            'telefono.required' => 'El telefono es obligatorio',
            'role_id.required' => 'El rol es obligatorio',
        ];
    }
}
