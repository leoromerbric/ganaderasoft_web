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
     * Obtiene los datos para el Reporte General.
     *
     * @param array $filters
     * @return array
     */
    public function getReporteGeneral(array $filters = []): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'data' => [], 'message' => 'Usuario no autenticado'];
        }

        try {
            $queryString = !empty($filters) ? '?' . http_build_query($filters) : '';
            $response = $this->get('/reportes/general' . $queryString);

            if (!($response['success'] ?? false)) {
                return ['success' => false, 'data' => []];
            }

            return ['success' => true, 'data' => $response['data'] ?? []];
        } catch (Exception $e) {
            Log::error('Error al consultar Reporte General: ' . $e->getMessage(), ['filters' => $filters]);
            return ['success' => false, 'data' => [], 'message' => 'Error al consultar Reporte General'];
        }
    }

    /**
     * Obtiene los datos para el Reporte Reproductivo.
     *
     * @param array $filters
     * @return array
     */
    public function getReporteReproductivo(array $filters = []): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'data' => [], 'message' => 'Usuario no autenticado'];
        }

        try {
            $queryString = !empty($filters) ? '?' . http_build_query($filters) : '';
            $response = $this->get('/reportes/reproductivo' . $queryString);

            if (!($response['success'] ?? false)) {
                return ['success' => false, 'data' => []];
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
        if (!session('user.token')) {
            return ['success' => false, 'data' => [], 'message' => 'Usuario no autenticado'];
        }

        try {
            $queryString = !empty($filters) ? '?' . http_build_query($filters) : '';
            $response = $this->get('/reportes/pesaje-leche' . $queryString);

            if (!($response['success'] ?? false)) {
                return ['success' => false, 'data' => []];
            }

            return ['success' => true, 'data' => $response['data'] ?? []];
        } catch (Exception $e) {
            Log::error('Error al consultar Reporte de Pesaje de Leche: ' . $e->getMessage(), ['filters' => $filters]);
            return ['success' => false, 'data' => [], 'message' => 'Error al consultar Reporte de Pesaje de Leche'];
        }
    }
}
