<?php

namespace App\Services\Contracts;

interface DashboardServiceInterface
{
    /**
     * Get KPI metrics for the dashboard
     */
    public function getKPIs(): array;

    /**
     * Get production chart data
     */
    public function getProductionChartData(): array;

    /**
     * Get recent alerts
     */
    public function getRecentAlerts(): array;
}
