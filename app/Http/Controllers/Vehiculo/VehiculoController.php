<?php

namespace App\Http\Controllers\Vehiculo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculo\VehiculoRequest;
use App\Services\Vehiculo\VehiculoService;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    protected $vehiculoService;

    public function __construct(VehiculoService $vehiculoService)
    {
        $this->vehiculoService = $vehiculoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');
        $vehiculos = $this->vehiculoService->getVehiculosByFilter($buscar);

        return response()->json([
            'total' => $vehiculos->total(),
            'vehiculos' => $vehiculos->map(function ($vehiculo) {
                return [
                    'id' => $vehiculo->id,
                    'placa' => $vehiculo->placa,
                    'color' => $vehiculo->color,
                    'marca' => $vehiculo->marca,
                    'tipo_vehiculo' => $vehiculo->tipo_vehiculo,
                    'conductor_id' => $vehiculo->conductor_id,
                    'propietario_id' => $vehiculo->propietario_id,
                    'conductor' => $vehiculo->conductor
                        ? trim($vehiculo->conductor->primer_nombre . ' ' .
                            ($vehiculo->conductor->segundo_nombre ? $vehiculo->conductor->segundo_nombre . ' ' : '') .
                            $vehiculo->conductor->apellidos)
                        : null,

                    'propietario' => $vehiculo->propietario
                        ? trim($vehiculo->propietario->primer_nombre . ' ' .
                            ($vehiculo->propietario->segundo_nombre ? $vehiculo->propietario->segundo_nombre . ' ' : '') .
                            $vehiculo->propietario->apellidos)
                        : null,
                    "created_format_at" => $vehiculo->created_at ? $vehiculo->created_at->format("Y-m-d h:i A") : ''
                ];
            })
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehiculoRequest $request)
    {
        try {
            $validated = $request->validated();
            $vehiculo = $this->vehiculoService->storeVehiculo($validated);

            if (!$vehiculo) {
                return response()->json([
                    'message' => 403,
                    'message_text' => 'No se pudo realizar la acción',
                    'vehiculo' => []
                ], 403);
            }

            return response()->json([
                'message' => 201,
                'message_text' => 'El vehículo se registró de manera exitosa',
                'vehiculo' => [
                    'id' => $vehiculo->id,
                    'placa' => $vehiculo->placa,
                    'color' => $vehiculo->color,
                    'marca' => $vehiculo->marca,
                    'tipo_vehiculo' => $vehiculo->tipo_vehiculo,
                    'conductor_id' => $vehiculo->conductor_id,
                    'propietario_id' => $vehiculo->propietario_id,
                    'conductor' => $vehiculo->conductor
                        ? trim($vehiculo->conductor->primer_nombre . ' ' .
                            ($vehiculo->conductor->segundo_nombre ? $vehiculo->conductor->segundo_nombre . ' ' : '') .
                            $vehiculo->conductor->apellidos)
                        : null,

                    'propietario' => $vehiculo->propietario
                        ? trim($vehiculo->propietario->primer_nombre . ' ' .
                            ($vehiculo->propietario->segundo_nombre ? $vehiculo->propietario->segundo_nombre . ' ' : '') .
                            $vehiculo->propietario->apellidos)
                        : null,
                    "created_format_at" => $vehiculo->created_at ? $vehiculo->created_at->format("Y-m-d h:i A") : ''
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 500,
                'message_text' => 'Ocurrió un error inesperado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VehiculoRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $vehiculo = $this->vehiculoService->updateVehiculo($validated, $id);

            return response()->json([
                'message' => 200,
                'message_text' => 'El vehículo se editó de manera exitosa',
                'vehiculo' => [
                    'id' => $vehiculo->id,
                    'placa' => $vehiculo->placa,
                    'color' => $vehiculo->color,
                    'marca' => $vehiculo->marca,
                    'tipo_vehiculo' => $vehiculo->tipo_vehiculo,
                    'conductor_id' => $vehiculo->conductor_id,
                    'propietario_id' => $vehiculo->propietario_id,
                    'conductor' => $vehiculo->conductor
                        ? trim($vehiculo->conductor->primer_nombre . ' ' .
                            ($vehiculo->conductor->segundo_nombre ? $vehiculo->conductor->segundo_nombre . ' ' : '') .
                            $vehiculo->conductor->apellidos)
                        : null,

                    'propietario' => $vehiculo->propietario
                        ? trim($vehiculo->propietario->primer_nombre . ' ' .
                            ($vehiculo->propietario->segundo_nombre ? $vehiculo->propietario->segundo_nombre . ' ' : '') .
                            $vehiculo->propietario->apellidos)
                        : null,
                    "created_format_at" => $vehiculo->created_at ? $vehiculo->created_at->format("Y-m-d h:i A") : ''
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 500,
                'message_text' => 'Ocurrió un error inesperado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
