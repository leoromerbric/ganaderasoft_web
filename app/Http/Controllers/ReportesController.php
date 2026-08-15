<?php

namespace App\Http\Controllers;

use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\ReportesServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Controlador encargado de gestionar las vistas y descargas de los Reportes del Sistema.
 */
class ReportesController extends Controller
{
    protected ReportesServiceInterface $reportesService;
    protected AuthServiceInterface $authService;

    public function __construct(
        ReportesServiceInterface $reportesService,
        AuthServiceInterface $authService
    ) {
        $this->reportesService = $reportesService;
        $this->authService = $authService;
    }

    /**
     * Prepara y formatea los datos y fechas para la plantilla de reportes.
     */
    private function prepareReportData(Request $request, array $reporteData): array
    {
        $fechaInicioInput = $request->filled('fecha_inicio') ? $request->query('fecha_inicio') : date('Y-m-01');
        $fechaFinInput = $request->filled('fecha_fin') ? $request->query('fecha_fin') : date('Y-m-d');

        $fechaEmision = Carbon::now()->format('d/m/Y h:i A');
        $fechaInicio = Carbon::parse($fechaInicioInput)->format('d/m/Y');
        $fechaFin = Carbon::parse($fechaFinInput)->format('d/m/Y');

        return [
            'reporte'          => $reporteData['data'] ?? [],
            'filters'          => $request->all(),
            'fechaEmision'     => $fechaEmision,
            'fechaInicio'      => $fechaInicio,
            'fechaFin'         => $fechaFin,
            'fechaInicioInput' => $fechaInicioInput,
            'fechaFinInput'    => $fechaFinInput,
        ];
    }

    /**
     * Muestra la vista del Reporte General.
     */
    public function indexGeneral(Request $request)
    {
        $reporteData = $this->reportesService->getReporteGeneral($request->all());
        return view('reportes.general', $this->prepareReportData($request, $reporteData));
    }

    /**
     * Muestra la vista del Reporte Reproductivo.
     */
    public function indexReproductivo(Request $request)
    {
        $reporteData = $this->reportesService->getReporteReproductivo($request->all());
        return view('reportes.reproductivo', $this->prepareReportData($request, $reporteData));
    }

    /**
     * Muestra la vista del Reporte de Pesaje de Leche.
     */
    public function indexPesajeLeche(Request $request)
    {
        $reporteData = $this->reportesService->getReportePesajeLeche($request->all());
        return view('reportes.pesaje-leche', $this->prepareReportData($request, $reporteData));
    }
}
