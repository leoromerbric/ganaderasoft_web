<?php

namespace App\Services\Api;

use App\Services\Contracts\ReportesServiceInterface;

/**
 * Servicio encargado de la comunicación con los endpoints de Reportes en la API V2.
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
        $response = $this->get('/reportes/general' . $this->buildQuery($filters));

        if (!($response['success'] ?? false)) {
            return ['success' => false, 'data' => [], 'message' => $response['message'] ?? 'Error al consultar Reporte General'];
        }

        return ['success' => true, 'data' => $response['data'] ?? []];
    }

    /**
     * Obtiene los datos para el Reporte Reproductivo.
     *
     * @param array $filters
     * @return array
     */
    public function getReporteReproductivo(array $filters = []): array
    {
        $response = $this->get('/reportes/reproductivo' . $this->buildQuery($filters));

        if (!($response['success'] ?? false)) {
            return ['success' => false, 'data' => [], 'message' => $response['message'] ?? 'Error al consultar Reporte Reproductivo'];
        }

        return ['success' => true, 'data' => $response['data'] ?? []];
    }

    /**
     * Obtiene los datos para el Reporte de Pesaje de Leche.
     *
     * @param array $filters
     * @return array
     */
    public function getReportePesajeLeche(array $filters = []): array
    {
        $response = $this->get('/reportes/pesaje-leche' . $this->buildQuery($filters));

        if (!($response['success'] ?? false)) {
            return ['success' => false, 'data' => [], 'message' => $response['message'] ?? 'Error al consultar Reporte de Pesaje de Leche'];
        }

        return ['success' => true, 'data' => $response['data'] ?? []];
    }
}
