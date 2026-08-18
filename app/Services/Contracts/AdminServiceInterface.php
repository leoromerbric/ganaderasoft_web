<?php

namespace App\Services\Contracts;

interface AdminServiceInterface
{
    /**
     * Obtener métricas y KPIs globales para el Dashboard de Administración.
     *
     * @return array
     */
    public function getDashboardKpis(): array;
}
