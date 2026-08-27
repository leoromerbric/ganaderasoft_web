<?php

namespace App\Services\Api;

use App\Services\Contracts\ReportesServiceInterface;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Servicio encargado de la comunicación con los endpoints de Reportes en la API v2.
 */
class ApiReportesService extends BaseApiService implements ReportesServiceInterface
{
    /**
     * Obtiene los datos para el Reporte General de Finca.
     *
     * @param array $filters
     * @return array
     */
    public function getReporteGeneral(array $filters = []): array
    {
        try {
            $queryString = !empty($filters) ? '?' . http_build_query($filters) : '';
            $response = $this->get('/reportes/general' . $queryString);

            if (!($response['success'] ?? false)) {
                return ['success' => false, 'data' => [], 'message' => $response['message'] ?? 'Error al obtener reporte general'];
            }

            return ['success' => true, 'data' => $response['data'] ?? []];
        } catch (Exception $e) {
            Log::error('Error al consultar Reporte General: ' . $e->getMessage(), ['filters' => $filters]);
            return ['success' => false, 'data' => [], 'message' => 'Error al consultar Reporte General'];
        }
    }

    /**
     * Obtiene los datos para la Historia de Lactancias (cálculo TIM P244, P270, P305).
     *
     * @param array $filters
     * @return array
     */
    public function getReporteLactancias(array $filters = []): array
    {
        try {
            $queryString = !empty($filters) ? '?' . http_build_query($filters) : '';
            $response = $this->get('/reportes/lactancias' . $queryString);

            if (!($response['success'] ?? false)) {
                return ['success' => false, 'data' => [], 'message' => $response['message'] ?? 'Error al obtener reporte de lactancias'];
            }

            return ['success' => true, 'data' => $response['data'] ?? []];
        } catch (Exception $e) {
            Log::error('Error al consultar Reporte de Lactancias: ' . $e->getMessage(), ['filters' => $filters]);
            return ['success' => false, 'data' => [], 'message' => 'Error al consultar Reporte de Lactancias'];
        }
    }

    /**
     * Obtiene los datos para el Reporte Reproductivo Consolidado.
     *
     * @param array $filters
     * @return array
     */
    public function getReporteReproductivo(array $filters = []): array
    {
        try {
            $queryString = !empty($filters) ? '?' . http_build_query($filters) : '';
            $response = $this->get('/reportes/reproductivo' . $queryString);

            if (!($response['success'] ?? false)) {
                return ['success' => false, 'data' => [], 'message' => $response['message'] ?? 'Error al obtener reporte reproductivo'];
            }

            return ['success' => true, 'data' => $response['data'] ?? []];
        } catch (Exception $e) {
            Log::error('Error al consultar Reporte Reproductivo: ' . $e->getMessage(), ['filters' => $filters]);
            return ['success' => false, 'data' => [], 'message' => 'Error al consultar Reporte Reproductivo'];
        }
    }

    /**
     * Obtiene los datos para el Reporte de Pesaje de Leche.
     *
     * @param array $filters
     * @return array
     */
    public function getReportePesajeLeche(array $filters = []): array
    {
        try {
            $queryString = !empty($filters) ? '?' . http_build_query($filters) : '';
            $response = $this->get('/reportes/pesaje-leche' . $queryString);

            if (!($response['success'] ?? false)) {
                return ['success' => false, 'data' => [], 'message' => $response['message'] ?? 'Error al obtener reporte de pesaje de leche'];
            }

            return ['success' => true, 'data' => $response['data'] ?? []];
        } catch (Exception $e) {
            Log::error('Error al consultar Reporte de Pesaje de Leche: ' . $e->getMessage(), ['filters' => $filters]);
            return ['success' => false, 'data' => [], 'message' => 'Error al consultar Reporte de Pesaje de Leche'];
        }
    }

    /**
     * Obtiene los datos para el Reporte de Rebaños.
     *
     * @param array $filters
     * @return array
     */
    public function getReporteRebanos(array $filters = []): array
    {
        try {
            $queryString = !empty($filters) ? '?' . http_build_query($filters) : '';
            $response = $this->get('/reportes/rebanos' . $queryString);

            if (!($response['success'] ?? false)) {
                return ['success' => false, 'data' => [], 'message' => $response['message'] ?? 'Error al obtener reporte de rebaños'];
            }

            return ['success' => true, 'data' => $response['data'] ?? []];
        } catch (Exception $e) {
            Log::error('Error al consultar Reporte de Rebaños: ' . $e->getMessage(), ['filters' => $filters]);
            return ['success' => false, 'data' => [], 'message' => 'Error al consultar Reporte de Rebaños'];
        }
    }
}
