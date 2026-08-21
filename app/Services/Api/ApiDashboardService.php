<?php

namespace App\Services\Api;

use App\Services\Contracts\DashboardServiceInterface;

class ApiDashboardService extends BaseApiService implements DashboardServiceInterface
{
    /**
     * Obtiene las estadísticas de fincas desde la API V2.
     *
     * @param int|null $fincaId
     * @return array
     */
    public function getFarmStatistics(?int $fincaId = null): array
    {
        return $this->get('/reportes/fincas' . $this->buildQuery(['finca_id' => $fincaId]));
    }

    /**
     * Obtiene la lista de fincas para el filtro del Dashboard.
     *
     * @return array
     */
    public function getFarms(): array
    {
        $stats = $this->getFarmStatistics();

        if (!isset($stats['success']) || !$stats['success'] || !isset($stats['data']['fincas']) || !is_array($stats['data']['fincas'])) {
            return [];
        }

        // Mapeo seguro V2 para asegurar atributos id y nombre
        return array_map(static function ($finca) {
            return [
                'id'                => $finca['finca_id'] ?? $finca['id'] ?? $finca['id_Finca'] ?? null,
                'nombre'            => $finca['nombre'] ?? $finca['Nombre'] ?? 'Sin Nombre',
                'cantidad_rebanos'  => $finca['cantidad_rebanos'] ?? 0,
                'cantidad_animales' => $finca['cantidad_animales'] ?? 0,
                'cantidad_personal' => $finca['cantidad_personal'] ?? 0,
            ];
        }, $stats['data']['fincas']);
    }

    /**
     * Obtiene métricas y KPIs para las tarjetas del Dashboard.
     *
     * @param int|null $fincaId
     * @return array
     */
    public function getKPIs(?int $fincaId = null): array
    {
        $stats = $this->getFarmStatistics($fincaId);

        if (!isset($stats['success']) || !$stats['success'] || !isset($stats['data']['resumen'])) {
            return $this->getDefaultKPIs();
        }

        $resumen = $stats['data']['resumen'];

        return [
            [
                'title' => 'Total Animales',
                'value' => number_format($resumen['total_animales'] ?? 0, 0, ',', '.'),
                'icon'  => '🐄',
                'color' => 'celeste',
            ],
            [
                'title' => 'Total Fincas',
                'value' => number_format($resumen['total_fincas'] ?? 0, 0, ',', '.'),
                'icon'  => '🏡',
                'color' => 'verde',
            ],
            [
                'title' => 'Total Rebaños',
                'value' => number_format($resumen['total_rebanos'] ?? 0, 0, ',', '.'),
                'icon'  => '🐑',
                'color' => 'azul',
            ],
            [
                'title' => 'Total Personal',
                'value' => number_format($resumen['total_personal'] ?? 0, 0, ',', '.'),
                'icon'  => '👥',
                'color' => 'negro',
            ],
        ];
    }

    /**
     * Obtiene la estructura de datos para los gráficos del Dashboard.
     *
     * @param int|null $fincaId
     * @return array
     */
    public function getProductionChartData(?int $fincaId = null): array
    {
        $stats = $this->getFarmStatistics($fincaId);

        if (!isset($stats['success']) || !$stats['success'] || !isset($stats['data'])) {
            return $this->getDefaultChartData();
        }

        $data = $stats['data'];

        // Gráfico de distribución de animales por sexo
        if (isset($data['animales_por_sexo'])) {
            $sexos = $data['animales_por_sexo'];
            return [
                'labels'   => ['Machos', 'Hembras'],
                'datasets' => [
                    [
                        'label'           => 'Animales por Sexo',
                        'data'            => [
                            $sexos['M'] ?? 0,
                            $sexos['F'] ?? 0,
                        ],
                        'backgroundColor' => [
                            'rgba(0, 123, 146, 0.85)',   // ganaderasoft-azul
                            'rgba(110, 193, 228, 0.85)',  // ganaderasoft-celeste
                        ],
                        'borderColor'     => [
                            'rgb(0, 123, 146)',
                            'rgb(110, 193, 228)',
                        ],
                        'borderWidth'     => 2,
                    ],
                ],
            ];
        }

        return $this->getDefaultChartData();
    }

    /**
     * Obtiene alertas recientes del sistema.
     *
     * @return array
     */
    public function getRecentAlerts(): array
    {
        return [
            [
                'fecha'   => date('d/m/Y H:i'),
                'nivel'   => 'media',
                'mensaje' => 'Conectado a la API Moderna V2 de GanaderaSoft',
            ],
        ];
    }

    /**
     * KPIs por defecto en caso de no poder conectar con la API.
     *
     * @return array
     */
    private function getDefaultKPIs(): array
    {
        return [
            [
                'title' => 'Total Animales',
                'value' => '0',
                'icon'  => '🐄',
                'color' => 'celeste',
            ],
            [
                'title' => 'Total Fincas',
                'value' => '0',
                'icon'  => '🏡',
                'color' => 'verde',
            ],
            [
                'title' => 'Total Rebaños',
                'value' => '0',
                'icon'  => '🐑',
                'color' => 'azul',
            ],
            [
                'title' => 'Total Personal',
                'value' => '0',
                'icon'  => '👥',
                'color' => 'negro',
            ],
        ];
    }

    /**
     * Datos de gráfico por defecto en caso de no haber datos.
     *
     * @return array
     */
    private function getDefaultChartData(): array
    {
        return [
            'labels'   => ['Sin Datos'],
            'datasets' => [
                [
                    'label'           => 'Datos no disponibles',
                    'data'            => [0],
                    'backgroundColor' => 'rgba(156, 163, 175, 0.5)',
                    'borderColor'     => 'rgb(107, 114, 128)',
                    'borderWidth'     => 2,
                ],
            ],
        ];
    }
}
