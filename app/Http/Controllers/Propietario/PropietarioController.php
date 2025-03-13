<?php

namespace App\Http\Controllers\Propietario;

use App\Http\Controllers\Controller;
use App\Http\Requests\Propietario\PropietarioRequest;
use App\Services\Propietario\PropietarioService;
use Illuminate\Http\Request;

class PropietarioController extends Controller
{

    protected $propietarioService;

    public function __construct(PropietarioService $propietarioService)
    {
        $this->propietarioService = $propietarioService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $buscar = $request->get('buscar');
        $propietarios = $this->propietarioService->getPropietariosByFilter($buscar);

        return response()->json([
            'total' => $propietarios->total(),
            'propietarios' => $propietarios->map(function ($propietario) {
                return [
                    'id' => $propietario->id,
                    'cedula' => $propietario->cedula,
                    'primer_nombre' => $propietario->primer_nombre,
                    'segundo_nombre' => is_null($propietario->segundo_nombre) ? '' : $propietario->segundo_nombre,
                    'apellidos' => $propietario->apellidos,
                    'direccion' => is_null($propietario->direccion) ? '' : $propietario->direccion,
                    'ciudad' => $propietario->ciudad,
                    'telefono' => $propietario->telefono,
                    "created_format_at" => $propietario->created_at ? $propietario->created_at->format("Y-m-d h:i A") : ''
                ];
            })
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PropietarioRequest $request)
    {
        try {
            $validated = $request->validated();

            $propietario = $this->propietarioService->storePropietario($validated);

            if ($propietario == false) {
                return response()->json([
                    'message' => 403,
                    'message_text' => 'No se pudo realizar la acción',
                    'user' => []
                ], 403);
            }
            return response()->json([
                'message' => 201,
                'message_text' => 'El propietario se registró de manera exitosa',
                'propietario' => [
                    'id' => $propietario->id,
                    'cedula' => $propietario->cedula,
                    'primer_nombre' => $propietario->primer_nombre,
                    'segundo_nombre' => is_null($propietario->segundo_nombre) ? '' : $propietario->segundo_nombre,
                    'apellidos' => $propietario->apellidos,
                    'direccion' => is_null($propietario->direccion) ? '' : $propietario->direccion,
                    'ciudad' => $propietario->ciudad,
                    'telefono' => $propietario->telefono,
                    "created_format_at" => $propietario->created_at ? $propietario->created_at->format("Y-m-d h:i A") : ''
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
    public function update(PropietarioRequest $request, string $id)
    {
        try {

            $validated = $request->validated();

            $propietario = $this->propietarioService->updatePropietario($validated, $request->id);

            return response()->json([
                'message' => 200,
                'message_text' => 'El propietario se editó de manera exitosa',
                'propietario' => [
                    'id' => $propietario->id,
                    'cedula' => $propietario->cedula,
                    'primer_nombre' => $propietario->primer_nombre,
                    'segundo_nombre' => is_null($propietario->segundo_nombre) ? '' : $propietario->segundo_nombre,
                    'apellidos' => $propietario->apellidos,
                    'direccion' => is_null($propietario->direccion) ? '' : $propietario->direccion,
                    'ciudad' => $propietario->ciudad,
                    'telefono' => $propietario->telefono,
                    "created_format_at" => $propietario->created_at ? $propietario->created_at->format("Y-m-d h:i A") : ''
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
