<?php

namespace App\Services\Contracts;

interface DashboardServiceInterface
{
    /**
     * Get KPI metrics for the dashboard
     */
    public function getKPIs(?int $fincaId = null): array;

    /**
     * Get production chart data
     */
    public function getProductionChartData(?int $fincaId = null): array;

    /**
     * Get recent alerts
     */
    public function getRecentAlerts(): array;

    /**
     * Get farm statistics (metrics report)
     */
    public function getFarmStatistics(?int $fincaId = null): array;

    /**
     * Get list of farms for filtering
     */
    public function getFarms(): array;
}
