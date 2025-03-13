<?php

namespace App\Http\Controllers\Informe;

use App\Http\Controllers\Controller;
use App\Services\Informe\InformeService;
use App\Services\Propietario\PropietarioService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InformeController extends Controller
{
    protected $informeService;
    protected $propietarioService;

    public function __construct(
        InformeService $informeService,
        PropietarioService $propietarioService
    ) {
        $this->informeService = $informeService;
        $this->propietarioService = $propietarioService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');
        $propietario_id = $request->get('propietario_id');

        $vehiculos = $this->informeService->getVehiculosByFilter($buscar, $propietario_id);

        return response()->json([
            'total' => $vehiculos->total(),
            'vehiculos' => $vehiculos->map(function ($vehiculo) {
                return [
                    'placa' => $vehiculo->placa,
                    'marca' => $vehiculo->marca,
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
                ];
            })
        ]);
    }

    public function pdf(Request $request)
    {
        $buscar = $request->get('buscar');
        $propietario_id = $request->get('propietario_id');
        $propietario_id = isset($propietario_id) && $propietario_id == 9999999 ? null : ($propietario_id ?? null);
        
        $vehiculos = $this->informeService->getVehiculosByFilterPdf($buscar, $propietario_id);

        if ($propietario_id === null) {
            $propietario = null; // O cualquier valor predeterminado
        } else {
            $propietario = $this->propietarioService->getPropietarioById($propietario_id);
        }

        $pdf = PDF::loadView(
            "informes.informe",
            compact(
                'vehiculos',
                'propietario',
            )
        );
        $pdf->set_paper('A4', 'portrait');
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->set_option('isPhpEnabled', true);
        $pdf->set_option('isFontSubsettingEnabled', true);

        return $pdf->stream('informe-' . Date('Y-m-d') . '-' . uniqid() . '.pdf');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
