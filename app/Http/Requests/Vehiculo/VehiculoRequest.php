<?php

namespace App\Http\Requests\Vehiculo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehiculoRequest extends FormRequest
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
        $vehiculoId = $this->route('id');

        return match ($vehiculoId ? 'PUT' : $this->method()) {
            'POST' => [
                'placa' => [
                    'required',
                    'string',
                    'max:10',
                    Rule::unique('vehiculos') // Valida que la placa sea única
                ],
                'color' => 'required|string|max:30',
                'marca' => 'required|string|max:50',
                'tipo_vehiculo' => 'required|string|max:50',
                'conductor_id' => [
                    'required',
                    'integer',
                    Rule::exists('conductores', 'id'), // Valida que el conductor exista
                    Rule::unique('vehiculos', 'conductor_id') 
                ],
                'propietario_id' => [
                    'required',
                    'integer',
                    Rule::exists('propietarios', 'id') // Valida que el propietario exista
                ],
            ],
            'PUT' => [
                'placa' => [
                    'required',
                    'string',
                    'max:10',
                    Rule::unique('vehiculos')->ignore($this->id) // Ignora el vehículo actual en actualización
                ],
                'color' => 'required|string|max:30',
                'marca' => 'required|string|max:50',
                'tipo_vehiculo' => 'required|string|max:50',
                'conductor_id' => [
                    'required',
                    'integer',
                    Rule::exists('conductores', 'id'),
                    Rule::unique('vehiculos', 'conductor_id')->ignore($this->id)
                ],
                'propietario_id' => [
                    'required',
                    'integer',
                    Rule::exists('propietarios', 'id')
                ],
            ]
        };
    }

    public function messages(): array
    {
        return [
            'placa.required' => 'La placa es obligatoria',
            'placa.string' => 'La placa debe ser una cadena de caracteres',
            'placa.max' => 'El máximo de caracteres para la placa es 10',
            'placa.unique' => 'Ya existe un vehículo con esta placa',

            'color.required' => 'El color es obligatorio',
            'color.string' => 'El color debe ser una cadena de caracteres',
            'color.max' => 'El máximo de caracteres para el color es 30',

            'marca.required' => 'La marca es obligatoria',
            'marca.string' => 'La marca debe ser una cadena de caracteres',
            'marca.max' => 'El máximo de caracteres para la marca es 50',

            'tipo_vehiculo.required' => 'El tipo de vehículo es obligatorio',
            'tipo_vehiculo.string' => 'El tipo de vehículo debe ser una cadena de caracteres',
            'tipo_vehiculo.max' => 'El máximo de caracteres para el tipo de vehículo es 50',

            'conductor_id.required' => 'El conductor es obligatorio',
            'conductor_id.integer' => 'El ID del conductor debe ser un número entero',
            'conductor_id.exists' => 'El conductor seleccionado no existe en la base de datos',
            'conductor_id.unique' => 'El conductor seleccionado ya está asignado a otro vehículo',

            'propietario_id.required' => 'El propietario es obligatorio',
            'propietario_id.integer' => 'El ID del propietario debe ser un número entero',
            'propietario_id.exists' => 'El propietario seleccionado no existe en la base de datos',
        ];
    }
}
