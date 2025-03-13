<?php

namespace App\Services\Vehiculo;

use App\Models\Vehiculo;
use Illuminate\Support\Facades\Log;

class VehiculoService
{
    public function getVehiculosByFilter($buscar)
    {
        try {
            return Vehiculo::with(['conductor', 'propietario'])
                ->when(!empty($buscar), function ($query) use ($buscar) {
                    $palabras = explode(' ', trim($buscar));

                    $query->where(function ($q) use ($palabras) {
                        foreach ($palabras as $palabra) {
                            $q->where(function ($subQuery) use ($palabra) {
                                $subQuery->where('placa', 'like', "%{$palabra}%")
                                    ->orWhere('marca', 'like', "%{$palabra}%")
                                    ->orWhere('tipo_vehiculo', 'like', "%{$palabra}%");
                            })
                                ->orWhereHas('conductor', function ($qConductor) use ($palabra) {
                                    $qConductor->where('primer_nombre', 'like', "%{$palabra}%")
                                        ->orWhere('segundo_nombre', 'like', "%{$palabra}%")
                                        ->orWhere('apellidos', 'like', "%{$palabra}%")
                                        ->orWhere('cedula', 'like', "%{$palabra}%");
                                })
                                ->orWhereHas('propietario', function ($qPropietario) use ($palabra) {
                                    $qPropietario->where('primer_nombre', 'like', "%{$palabra}%")
                                        ->orWhere('segundo_nombre', 'like', "%{$palabra}%")
                                        ->orWhere('apellidos', 'like', "%{$palabra}%")
                                        ->orWhere('cedula', 'like', "%{$palabra}%");
                                });
                        }
                    });
                })
                ->orderBy('id', 'desc')
                ->paginate(20);
        } catch (\Exception $e) {
            Log::error('Error en getVehiculosByFilter: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los vehículos.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getVehiculoById($id)
    {
        return Vehiculo::with(['conductor', 'propietario'])->findOrFail($id);
    }

    public function storeVehiculo($request)
    {
        try {
            $data = is_array($request) ? $request : $request->toArray();

            // Validación de tipo_vehiculo
            $tiposValidos = ['particular', 'publico'];
            if (!in_array($data['tipo_vehiculo'], $tiposValidos)) {
                throw new \Exception("El tipo de vehículo debe ser 'particular' o 'publico'.");
            }

            // Convertir valores a enteros
            $data['conductor_id'] = (int) $data['conductor_id'];
            $data['propietario_id'] = (int) $data['propietario_id'];

            $vehiculo = Vehiculo::create($data);

            if ($vehiculo) {
                return $vehiculo;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Error en storeVehiculo: ' . $e->getMessage());
            return false;
        }
    }

    public function updateVehiculo($request, $id)
    {
        try {
            $vehiculo = Vehiculo::findOrFail($id);
            if (!$vehiculo) {
                return false;
            }

            $data = is_array($request) ? $request : $request->toArray();

            // Validación de tipo_vehiculo
            $tiposValidos = ['particular', 'publico'];
            if (!in_array($data['tipo_vehiculo'], $tiposValidos)) {
                throw new \Exception("El tipo de vehículo debe ser 'particular' o 'publico'.");
            }

            if (isset($data['conductor_id'])) {
                $data['conductor_id'] = (int) $data['conductor_id'];
            }

            if (isset($data['propietario_id'])) {
                $data['propietario_id'] = (int) $data['propietario_id'];
            }

            $vehiculo->update($data);

            return $vehiculo;
        } catch (\Exception $e) {
            Log::error('Error en updateVehiculo: ' . $e->getMessage());
            return false;
        }
    }
}
