<?php

namespace App\Services\Informe;

use App\Models\Vehiculo;
use Illuminate\Support\Facades\Log;

class InformeService
{
    public function getVehiculosByFilter($buscar, $propietario_id)
    {
        $propietario_id = isset($propietario_id) && $propietario_id == 9999999 ? null : ($propietario_id ?? null);
        
        try {
            return Vehiculo::with(['conductor', 'propietario'])
                ->when(!empty($buscar), function ($query) use ($buscar) {
                    $palabras = explode(' ', trim($buscar));

                    $query->where(function ($q) use ($palabras) {
                        foreach ($palabras as $palabra) {
                            $q->where('placa', 'like', "%{$palabra}%")
                                ->orWhere('marca', 'like', "%{$palabra}%");
                        }
                    });
                })
                ->when(!empty($propietario_id), function ($query) use ($propietario_id) {
                    $query->where('propietario_id', $propietario_id);
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

    public function getVehiculosByFilterPdf($buscar, $propietario_id)
    {
        $propietario_id = isset($propietario_id) && $propietario_id == 9999999 ? null : ($propietario_id ?? null);
        
        try {
            return Vehiculo::with(['conductor', 'propietario'])
                ->when(!empty($buscar), function ($query) use ($buscar) {
                    $palabras = explode(' ', trim($buscar));

                    $query->where(function ($q) use ($palabras) {
                        foreach ($palabras as $palabra) {
                            $q->where('placa', 'like', "%{$palabra}%")
                                ->orWhere('marca', 'like', "%{$palabra}%");
                        }
                    });
                })
                ->when(!empty($propietario_id), function ($query) use ($propietario_id) {
                    $query->where('propietario_id', $propietario_id);
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error en getVehiculosByFilter: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los vehículos.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
