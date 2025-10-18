<?php

namespace App\Services\Mock;

use App\Services\Contracts\FincasServiceInterface;

class MockFincasService implements FincasServiceInterface
{
    /**
     * Get list of fincas (mock data based on API response structure)
     */
    public function getFincas(): array
    {
        return [
            'success' => true,
            'message' => 'Lista de fincas',
            'data' => [
                'current_page' => 1,
                'data' => [
                    [
                        'id_Finca' => 15,
                        'id_Propietario' => 6,
                        'Nombre' => 'Finca La Nueva Esperanza',
                        'Explotacion_Tipo' => 'Bovinos y Porcinos',
                        'archivado' => false,
                        'created_at' => '2025-07-28T04:59:21.000000Z',
                        'updated_at' => '2025-07-28T05:00:31.000000Z',
                        'propietario' => [
                            'id' => 6,
                            'id_Personal' => 17873216,
                            'Nombre' => 'Leonel',
                            'Apellido' => 'Romero',
                            'Telefono' => '04140659739',
                            'archivado' => false,
                        ],
                        'terreno' => null,
                    ],
                    [
                        'id_Finca' => 16,
                        'id_Propietario' => 6,
                        'Nombre' => 'Finca La Romería',
                        'Explotacion_Tipo' => 'Bovinos',
                        'archivado' => false,
                        'created_at' => '2025-07-28T19:37:02.000000Z',
                        'updated_at' => '2025-07-28T19:37:02.000000Z',
                        'propietario' => [
                            'id' => 6,
                            'id_Personal' => 17873216,
                            'Nombre' => 'Leonel',
                            'Apellido' => 'Romero',
                            'Telefono' => '04140659739',
                            'archivado' => false,
                        ],
                        'terreno' => null,
                    ],
                    [
                        'id_Finca' => 17,
                        'id_Propietario' => 6,
                        'Nombre' => 'Finca Demo Updated',
                        'Explotacion_Tipo' => 'Bovinos y Porcinos',
                        'archivado' => false,
                        'created_at' => '2025-08-16T18:03:03.000000Z',
                        'updated_at' => '2025-08-16T18:19:31.000000Z',
                        'propietario' => [
                            'id' => 6,
                            'id_Personal' => 17873216,
                            'Nombre' => 'Leonel',
                            'Apellido' => 'Romero',
                            'Telefono' => '04140659739',
                            'archivado' => false,
                        ],
                        'terreno' => [
                            'id_Terreno' => 15,
                            'id_Finca' => 17,
                            'Superficie' => 150,
                            'Relieve' => 'Ondulado',
                            'Suelo_Textura' => 'Franco',
                            'ph_Suelo' => '6',
                        ],
                    ],
                ],
                'first_page_url' => 'http://localhost:8000/api/fincas?page=1',
                'from' => 1,
                'last_page' => 1,
                'last_page_url' => 'http://localhost:8000/api/fincas?page=1',
                'next_page_url' => null,
                'path' => 'http://localhost:8000/api/fincas',
                'per_page' => 15,
                'prev_page_url' => null,
                'to' => 3,
                'total' => 3,
            ],
        ];
    }
}
