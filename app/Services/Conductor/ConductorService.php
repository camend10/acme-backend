<?php

namespace App\Services\Conductor;

use App\Models\Conductor;
use Illuminate\Support\Facades\Log;

class ConductorService
{
    public function getConductoresByFilter($buscar)
    {
        try {

            return Conductor::when(!empty($buscar), function ($query) use ($buscar) {
                $palabras = explode(' ', trim($buscar));

                $query->where(function ($q) use ($palabras) {
                    foreach ($palabras as $palabra) {
                        $q->where(function ($subQuery) use ($palabra) {
                            $subQuery->where('primer_nombre', 'like', "%{$palabra}%")
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
            Log::error('Error en getConductoresByFilter: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los conductores.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getConductorById($id)
    {
        return Conductor::findOrFail($id);
    }

    public function storeConductor($request)
    {
        try {

            $data = is_array($request) ? $request : $request->toArray();
            $conductor = Conductor::create($data);

            if ($conductor) {
                return $conductor;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Error en storeConductor: ' . $e->getMessage());
            return false;
        }
    }

    public function updateConductor($request, $id)
    {
        try {

            $conductor = Conductor::findOrFail($id);

            if (!$conductor) {
                return false;
            }

            $data = is_array($request) ? $request : $request->toArray();

            $conductor->update($data);

            return $conductor;
        } catch (\Exception $e) {
            // Registrar error en logs
            Log::error('Error en updateConductor: ' . $e->getMessage());
            return false;
        }
    }
}
