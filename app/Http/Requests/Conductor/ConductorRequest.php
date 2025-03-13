<?php

namespace App\Http\Requests\Conductor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConductorRequest extends FormRequest
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
            'cedula' => [
                'required',
                'string',
                'max:20',
                Rule::unique('conductores') // Asegura que la cédula sea única en la tabla propietarios
            ],
            'primer_nombre' => 'required|string|max:50',
            'segundo_nombre' => 'nullable|string|max:50',
            'apellidos' => 'required|string|max:100',
            'direccion' => 'nullable|string|max:150',
            'telefono' => 'required|max:20',
            'ciudad' => 'nullable|string|max:100',
        ],
        'PUT' => [
            'cedula' => [
                'required',
                'string',
                'max:20',
                Rule::unique('conductores')->ignore($this->id) // Ignora el usuario actual en la actualización
            ],
            'primer_nombre' => 'required|string|max:50',
            'segundo_nombre' => 'nullable|string|max:50',
            'apellidos' => 'required|string|max:100',
            'direccion' => 'nullable|string|max:150',
            'telefono' => 'required|max:20',
            'ciudad' => 'nullable|string|max:100',
        ]
        };
    }

    public function messages(): array
    {
        return [
            'cedula.required' => 'La cédula es obligatoria',
            'cedula.string' => 'La cédula debe ser una cadena de caracteres',
            'cedula.max' => 'El máximo de caracteres para la cédula es 20',
            'cedula.unique' => 'Ya existe un conductor con esta cédula',
            
            'primer_nombre.required' => 'El primer nombre es obligatorio',
            'primer_nombre.string' => 'El primer nombre debe ser una cadena de caracteres',
            'primer_nombre.max' => 'El máximo de caracteres para el primer nombre es 50',
    
            'segundo_nombre.string' => 'El segundo nombre debe ser una cadena de caracteres',
            'segundo_nombre.max' => 'El máximo de caracteres para el segundo nombre es 50',
    
            'apellidos.required' => 'Los apellidos son obligatorios',
            'apellidos.string' => 'Los apellidos deben ser una cadena de caracteres',
            'apellidos.max' => 'El máximo de caracteres para los apellidos es 100',
    
            'direccion.string' => 'La dirección debe ser una cadena de caracteres',
            'direccion.max' => 'El máximo de caracteres para la dirección es 150',
    
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.max' => 'El máximo de caracteres para el teléfono es 20',
    
            'ciudad.string' => 'La ciudad debe ser una cadena de caracteres',
            'ciudad.max' => 'El máximo de caracteres para la ciudad es 100',
        ];
    }
}
