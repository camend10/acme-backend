<?php

namespace App\Http\Controllers\Generales;

use App\Http\Controllers\Controller;
use App\Services\Generales\GeneralService;
use Illuminate\Http\Request;

class GeneralesController extends Controller
{
    protected $generalService;

    public function __construct(GeneralService $generalService)
    {
        $this->generalService = $generalService;
    }

    public function configuraciones(Request $request)
    {
        $roles = $this->generalService->roles();
        $conductores = $this->generalService->conductores();
        $propietarios = $this->generalService->propietarios();
        $vehiculos = $this->generalService->vehiculos();
        $usuarios = $this->generalService->usuarios();

        if ($roles) {
            return response()->json([
                'roles' => $roles,
                'conductores' => $conductores,
                'propietarios' => $propietarios,
                'vehiculos' => $vehiculos,
                'total_usuarios' => count($usuarios),
                'total_propietarios' => count($propietarios),
                'total_conductores' => count($conductores),
                'total_vehiculos' => count($vehiculos),
            ], 200);
        } else {
            return response()->json([
                'message' => 403,
                'error' => "Lo sentimos, ocurrió un error en el servidor: ",
            ], 500);
        }
    }
}
