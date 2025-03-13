<?php

namespace App\Services\Propietario;

use App\Models\Propietario;
use Illuminate\Support\Facades\Log;

class PropietarioService
{
    public function getPropietariosByFilter($buscar)
    {
        try {

            return Propietario::when(!empty($buscar), function ($query) use ($buscar) {
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
            Log::error('Error en getPropietariosByFilter: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los propietarios.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPropietarioById($id)
    {
        return Propietario::findOrFail($id);
    }

    public function storePropietario($request)
    {
        try {

            $data = is_array($request) ? $request : $request->toArray();
            $propietario = Propietario::create($data);

            if ($propietario) {
                return $propietario;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Error en storePropietario: ' . $e->getMessage());
            return false;
        }
    }

    public function updatePropietario($request, $id)
    {
        try {

            $propietario = Propietario::findOrFail($id);

            if (!$propietario) {
                return false;
            }

            $data = is_array($request) ? $request : $request->toArray();

            $propietario->update($data);

            return $propietario;
        } catch (\Exception $e) {
            // Registrar error en logs
            Log::error('Error en updatePropietario: ' . $e->getMessage());
            return false;
        }
    }
}
