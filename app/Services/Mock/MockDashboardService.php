<?php

namespace App\Services\Mock;

use App\Services\Contracts\DashboardServiceInterface;

class MockDashboardService implements DashboardServiceInterface
{
    /**
     * Get KPI metrics for the dashboard
     */
    public function getKPIs(?int $fincaId = null): array
    {
        return [
            [
                'title' => 'Total Animales',
                'value' => '1,247',
                'icon' => '🐄',
                'color' => 'celeste',
            ],
            [
                'title' => 'Total Fincas',
                'value' => '18',
                'icon' => '🏡',
                'color' => 'verde',
            ],
            [
                'title' => 'Producción Diaria (L)',
                'value' => '4,582',
                'icon' => '🥛',
                'color' => 'azul',
            ],
            [
                'title' => 'Alertas Activas',
                'value' => '7',
                'icon' => '⚠️',
                'color' => 'negro',
            ],
        ];
    }

    /**
     * Get production chart data (last 7 days)
     */
    public function getProductionChartData(?int $fincaId = null): array
    {
        return [
            'labels' => ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
            'datasets' => [
                [
                    'label' => 'Producción de Leche (Litros)',
                    'data' => [4200, 4350, 4150, 4580, 4420, 4650, 4582],
                    'backgroundColor' => 'rgba(110, 193, 228, 0.2)',
                    'borderColor' => '#6EC1E4',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                ],
            ],
        ];
    }

    /**
     * Get farm statistics (mock)
     */
    public function getFarmStatistics(?int $fincaId = null): array
    {
        return [
            'success' => true,
            'message' => 'Mock data',
            'data' => []
        ];
    }

    /**
     * Get list of farms
     */
    public function getFarms(): array
    {
        return [
            ['id_Finca' => 1, 'Nombre' => 'Finca Demo 1'],
            ['id_Finca' => 2, 'Nombre' => 'Finca Demo 2'],
        ];
    }

    /**
     * Get recent alerts
     */
    public function getRecentAlerts(): array
    {
        return [
            [
                'fecha' => '17/10/2025 08:30',
                'nivel' => 'alta',
                'mensaje' => 'Animal #1024 requiere atención veterinaria',
            ],
            [
                'fecha' => '17/10/2025 07:15',
                'nivel' => 'media',
                'mensaje' => 'Bajo rendimiento de producción en Finca "El Paraíso"',
            ],
            [
                'fecha' => '16/10/2025 18:45',
                'nivel' => 'baja',
                'mensaje' => 'Recordatorio: Vacunación programada para mañana',
            ],
            [
                'fecha' => '16/10/2025 14:20',
                'nivel' => 'alta',
                'mensaje' => 'Temperatura elevada detectada en el ganado de Sector A',
            ],
            [
                'fecha' => '16/10/2025 09:10',
                'nivel' => 'media',
                'mensaje' => 'Stock de alimento bajo en depósito principal',
            ],
        ];
    }
}
