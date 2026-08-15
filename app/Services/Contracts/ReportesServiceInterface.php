<?php

namespace App\Services\Contracts;

interface ReportesServiceInterface
{
    /**
     * Obtiene los datos para el Reporte General.
     *
     * @param array $filters
     * @return array
     */
    public function getReporteGeneral(array $filters = []): array;

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
}
