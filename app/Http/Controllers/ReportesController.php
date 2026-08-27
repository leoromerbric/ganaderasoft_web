<?php

namespace App\Http\Controllers;

use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\FincasServiceInterface;
use App\Services\Contracts\RebanosServiceInterface;
use App\Services\Contracts\ReportesServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Controlador encargado de gestionar las vistas y descargas de los Reportes del Sistema.
 */
class ReportesController extends Controller
{
    public function __construct(
        protected ReportesServiceInterface $reportesService,
        protected AuthServiceInterface $authService,
        protected FincasServiceInterface $fincasService,
        protected RebanosServiceInterface $rebanosService
    ) {
    }

    /**
     * Prepara y formatea los datos, filtros y catálogos para las vistas de reportes.
     */
    private function prepareReportData(Request $request, array $reporteData): array
    {
        $selectedFinca = session('selected_finca');
        $reportFincaId = $reporteData['data']['finca']['id'] ?? null;
        $fincaId = $reportFincaId ?? ($request->filled('finca_id') ? (int) $request->query('finca_id') : ($selectedFinca['id'] ?? null));

        $fechaInicioInput = $request->filled('fecha_inicio') ? $request->query('fecha_inicio') : date('Y-m-01');
        $fechaFinInput = $request->filled('fecha_fin') ? $request->query('fecha_fin') : date('Y-m-d');

        $fechaEmision = Carbon::now()->format('d/m/Y h:i A');
        $fechaInicio = Carbon::parse($fechaInicioInput)->format('d/m/Y');
        $fechaFin = Carbon::parse($fechaFinInput)->format('d/m/Y');

        // Cargar fincas disponibles para el filtro
        $fincasResponse = $this->fincasService->getFincas();
        $rawFincas = ($fincasResponse['success'] ?? false) ? ($fincasResponse['data']['data'] ?? $fincasResponse['data'] ?? []) : [];
        
        $fincas = [];
        if (is_array($rawFincas)) {
            foreach ($rawFincas as $f) {
                if (is_array($f) && isset($f['id'], $f['nombre'])) {
                    $fincas[] = $f;
                }
            }
        }

        // Si se seleccionó una finca válida y difiere de la sesión activa, sincronizarla
        if (!empty($fincaId) && is_array($fincas)) {
            foreach ($fincas as $f) {
                if ($f['id'] == $fincaId && ($selectedFinca['id'] ?? null) != $fincaId) {
                    session(['selected_finca' => $f]);
                    break;
                }
            }
        }

        return [
            'reporte'          => $reporteData['data'] ?? [],
            'filters'          => $request->all(),
            'fincas'           => $fincas,
            'fincaId'          => $fincaId,
            'fechaEmision'     => $fechaEmision,
            'fechaInicio'      => $fechaInicio,
            'fechaFin'         => $fechaFin,
            'fechaInicioInput' => $fechaInicioInput,
            'fechaFinInput'    => $fechaFinInput,
        ];
    }

    /**
     * Resuelve los filtros asegurando el finca_id activo si no viene explícito.
     */
    private function resolveFilters(Request $request): array
    {
        $filters = array_filter($request->all(), fn($v) => $v !== null && $v !== '');
        if (empty($filters['finca_id'])) {
            $selectedFinca = session('selected_finca');
            if (!empty($selectedFinca['id'])) {
                $filters['finca_id'] = $selectedFinca['id'];
            }
        }
        return $filters;
    }

    /**
     * Muestra la vista del Reporte General de Finca.
     */
    public function indexGeneral(Request $request)
    {
        $filters = $this->resolveFilters($request);
        $reporteData = $this->reportesService->getReporteGeneral($filters);
        return view('reportes.general', $this->prepareReportData($request, $reporteData));
    }

    /**
     * Muestra la vista de Historia de Lactancias (cálculo TIM P244, P270, P305).
     */
    public function indexLactancias(Request $request)
    {
        $filters = $this->resolveFilters($request);
        $reporteData = $this->reportesService->getReporteLactancias($filters);
        return view('reportes.lactancias', $this->prepareReportData($request, $reporteData));
    }

    /**
     * Muestra la vista del Reporte Reproductivo.
     */
    public function indexReproductivo(Request $request)
    {
        $filters = $this->resolveFilters($request);
        $reporteData = $this->reportesService->getReporteReproductivo($filters);
        return view('reportes.reproductivo', $this->prepareReportData($request, $reporteData));
    }

    /**
     * Muestra la vista del Reporte de Pesaje de Leche.
     */
    public function indexPesajeLeche(Request $request)
    {
        $filters = $this->resolveFilters($request);
        $reporteData = $this->reportesService->getReportePesajeLeche($filters);
        return view('reportes.pesaje-leche', $this->prepareReportData($request, $reporteData));
    }

    /**
     * Muestra la vista del Reporte de Rebaños.
     */
    public function indexRebanos(Request $request)
    {
        $filters = $this->resolveFilters($request);
        $reporteData = $this->reportesService->getReporteRebanos($filters);
        return view('reportes.rebanos', $this->prepareReportData($request, $reporteData));
    }
}
