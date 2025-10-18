<?php

namespace App\Services\Api;

use App\Services\Contracts\DashboardServiceInterface;

class ApiDashboardService extends BaseApiService implements DashboardServiceInterface
{
    /**
     * Get farm statistics from API
     */
    public function getFarmStatistics(?int $fincaId = null): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $endpoint = '/reportes/fincas';
        if ($fincaId) {
            $endpoint .= '?id_finca=' . $fincaId;
        }

        $response = $this->get($endpoint, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
        ]);

        return $response;
    }

    /**
     * Get list of farms for filtering
     */
    public function getFarms(): array
    {
        $stats = $this->getFarmStatistics();
        
        if (!$stats['success'] || !isset($stats['data']['fincas'])) {
            return [];
        }

        return $stats['data']['fincas'];
    }

    /**
     * Get KPI metrics for the dashboard
     */
    public function getKPIs(?int $fincaId = null): array
    {
        $stats = $this->getFarmStatistics($fincaId);
        
        if (!$stats['success'] || !isset($stats['data']['resumen'])) {
            return $this->getDefaultKPIs();
        }

        $resumen = $stats['data']['resumen'];
        
        return [
            [
                'title' => 'Total Animales',
                'value' => number_format($resumen['total_animales'], 0, ',', '.'),
                'icon' => '🐄',
                'color' => 'celeste',
            ],
            [
                'title' => 'Total Fincas',
                'value' => number_format($resumen['total_fincas'], 0, ',', '.'),
                'icon' => '🏡',
                'color' => 'verde',
            ],
            [
                'title' => 'Total Rebaños',
                'value' => number_format($resumen['total_rebanos'], 0, ',', '.'),
                'icon' => '🐑',
                'color' => 'azul',
            ],
            [
                'title' => 'Total Personal',
                'value' => number_format($resumen['total_personal'], 0, ',', '.'),
                'icon' => '👥',
                'color' => 'negro',
            ],
        ];
    }

    /**
     * Get production chart data
     */
    public function getProductionChartData(?int $fincaId = null): array
    {
        $stats = $this->getFarmStatistics($fincaId);
        
        if (!$stats['success'] || !isset($stats['data'])) {
            return $this->getDefaultChartData();
        }

        $data = $stats['data'];

        // Create chart for animals by sex
        if (isset($data['animales_por_sexo'])) {
            $sexos = $data['animales_por_sexo'];
            return [
                'labels' => ['Machos', 'Hembras'],
                'datasets' => [
                    [
                        'label' => 'Animales por Sexo',
                        'data' => [
                            $sexos['M'] ?? 0,
                            $sexos['F'] ?? 0
                        ],
                        'backgroundColor' => [
                            'rgba(110, 193, 228, 0.6)',
                            'rgba(179, 211, 53, 0.6)'
                        ],
                        'borderColor' => [
                            '#6EC1E4',
                            '#B3D335'
                        ],
                        'borderWidth' => 2,
                    ],
                ],
            ];
        }

        return $this->getDefaultChartData();
    }

    /**
     * Get recent alerts
     */
    public function getRecentAlerts(): array
    {
        return [
            [
                'fecha' => date('d/m/Y H:i'),
                'nivel' => 'media',
                'mensaje' => 'Sistema conectado con el servidor de API',
            ],
        ];
    }

    /**
     * Default KPIs when API fails
     */
    private function getDefaultKPIs(): array
    {
        return [
            [
                'title' => 'Total Animales',
                'value' => '0',
                'icon' => '🐄',
                'color' => 'celeste',
            ],
            [
                'title' => 'Total Fincas',
                'value' => '0',
                'icon' => '🏡',
                'color' => 'verde',
            ],
            [
                'title' => 'Total Rebaños',
                'value' => '0',
                'icon' => '🐑',
                'color' => 'azul',
            ],
            [
                'title' => 'Total Personal',
                'value' => '0',
                'icon' => '👥',
                'color' => 'negro',
            ],
        ];
    }

    /**
     * Default chart data when API fails
     */
    private function getDefaultChartData(): array
    {
        return [
            'labels' => ['Sin Datos'],
            'datasets' => [
                [
                    'label' => 'Datos no disponibles',
                    'data' => [0],
                    'backgroundColor' => 'rgba(110, 193, 228, 0.2)',
                    'borderColor' => '#6EC1E4',
                    'borderWidth' => 2,
                ],
            ],
        ];
    }
}
