<?php

namespace App\Http\Controllers\Conductor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Conductor\ConductorRequest;
use App\Services\Conductor\ConductorService;
use Illuminate\Http\Request;

class ConductorController extends Controller
{
    protected $conductorService;

    public function __construct(ConductorService $conductorService)
    {
        $this->conductorService = $conductorService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');
        $conductores = $this->conductorService->getConductoresByFilter($buscar);

        return response()->json([
            'total' => $conductores->total(),
            'conductores' => $conductores->map(function ($conductor) {
                return [
                    'id' => $conductor->id,
                    'cedula' => $conductor->cedula,
                    'primer_nombre' => $conductor->primer_nombre,
                    'segundo_nombre' => is_null($conductor->segundo_nombre) ? '' : $conductor->segundo_nombre,
                    'apellidos' => $conductor->apellidos,
                    'direccion' => is_null($conductor->direccion) ? '' : $conductor->direccion,
                    'ciudad' => $conductor->ciudad,
                    'telefono' => $conductor->telefono,
                    "created_format_at" => $conductor->created_at ? $conductor->created_at->format("Y-m-d h:i A") : ''
                ];
            })
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConductorRequest $request)
    {
        try {
            $validated = $request->validated();

            $conductor = $this->conductorService->storeConductor($validated);

            if ($conductor == false) {
                return response()->json([
                    'message' => 403,
                    'message_text' => 'No se pudo realizar la acción',
                    'user' => []
                ], 403);
            }
            return response()->json([
                'message' => 201,
                'message_text' => 'El conductor se registró de manera exitosa',
                'conductor' => [
                    'id' => $conductor->id,
                    'cedula' => $conductor->cedula,
                    'primer_nombre' => $conductor->primer_nombre,
                    'segundo_nombre' => is_null($conductor->segundo_nombre) ? '' : $conductor->segundo_nombre,
                    'apellidos' => $conductor->apellidos,
                    'direccion' => is_null($conductor->direccion) ? '' : $conductor->direccion,
                    'ciudad' => $conductor->ciudad,
                    'telefono' => $conductor->telefono,
                    "created_format_at" => $conductor->created_at ? $conductor->created_at->format("Y-m-d h:i A") : ''
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
    public function update(ConductorRequest $request, string $id)
    {
        try {

            $validated = $request->validated();

            $conductor = $this->conductorService->updateConductor($validated, $request->id);

            return response()->json([
                'message' => 200,
                'message_text' => 'El conductor se editó de manera exitosa',
                'conductor' => [
                    'id' => $conductor->id,
                    'cedula' => $conductor->cedula,
                    'primer_nombre' => $conductor->primer_nombre,
                    'segundo_nombre' => is_null($conductor->segundo_nombre) ? '' : $conductor->segundo_nombre,
                    'apellidos' => $conductor->apellidos,
                    'direccion' => is_null($conductor->direccion) ? '' : $conductor->direccion,
                    'ciudad' => $conductor->ciudad,
                    'telefono' => $conductor->telefono,
                    "created_format_at" => $conductor->created_at ? $conductor->created_at->format("Y-m-d h:i A") : ''
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
