<?php

namespace App\Services\Contracts;

interface ReportesServiceInterface
{
    /**
     * Obtiene los datos para el Reporte General de Finca.
     *
     * @param array $filters
     * @return array
     */
    public function getReporteGeneral(array $filters = []): array;

    /**
     * Obtiene los datos para la Historia de Lactancias con cálculo TIM.
     *
     * @param array $filters
     * @return array
     */
    public function getReporteLactancias(array $filters = []): array;

    /**
     * Obtiene los datos para el Reporte Reproductivo.
     *
     * @param array $filters
     * @return array
     */
    public function getReporteReproductivo(array $filters = []): array;

    /**
     * Obtiene los datos para el Reporte de Pesaje de Leche.
     *
     * @param array $filters
     * @return array
     */
    public function getReportePesajeLeche(array $filters = []): array;

    /**
     * Obtiene los datos para el Reporte de Rebaños.
     *
     * @param array $filters
     * @return array
     */
    public function getReporteRebanos(array $filters = []): array;
}
