<?php

namespace App\Services\Mock;

use App\Services\Contracts\RebanosServiceInterface;

class MockRebanosService implements RebanosServiceInterface
{
    /**
     * Get list of rebaños (mock data based on API response structure)
     */
    public function getRebanos(): array
    {
        return [
            'success' => true,
            'message' => 'Lista de rebaños',
            'data' => [
                'current_page' => 1,
                'data' => [
                    [
                        'id_Rebano' => 6,
                        'id_Finca' => 15,
                        'Nombre' => 'Rebaño Principal',
                        'archivado' => false,
                        'created_at' => '2025-08-18T21:30:56.000000Z',
                        'updated_at' => '2025-08-18T21:30:56.000000Z',
                        'finca' => [
                            'id_Finca' => 15,
                            'id_Propietario' => 6,
                            'Nombre' => 'Finca La Nueva Esperanza',
                            'Explotacion_Tipo' => 'Bovinos',
                            'archivado' => false,
                            'created_at' => '2025-08-18T00:59:56.000000Z',
                            'updated_at' => '2025-08-18T00:59:56.000000Z',
                            'propietario' => [
                                'id' => 6,
                                'id_Personal' => 17873216,
                                'Nombre' => 'Leonel',
                                'Apellido' => 'Romero',
                                'Telefono' => '04140659739',
                                'archivado' => false,
                            ],
                        ],
                        'animales' => [
                            [
                                'id_Animal' => 13,
                                'id_Rebano' => 6,
                                'Nombre' => 'Vaca Bella',
                                'codigo_animal' => 'ANIMAL-001',
                                'Sexo' => 'F',
                                'fecha_nacimiento' => '2025-03-15T00:00:00.000000Z',
                                'Procedencia' => 'Finca Origen',
                                'archivado' => false,
                            ],
                            [
                                'id_Animal' => 14,
                                'id_Rebano' => 6,
                                'Nombre' => 'Toro Max',
                                'codigo_animal' => 'ANIMAL-002',
                                'Sexo' => 'M',
                                'fecha_nacimiento' => '2024-01-10T00:00:00.000000Z',
                                'Procedencia' => 'Local',
                                'archivado' => false,
                            ],
                        ],
                    ],
                    [
                        'id_Rebano' => 7,
                        'id_Finca' => 16,
                        'Nombre' => 'Rebaño Secundario',
                        'archivado' => false,
                        'created_at' => '2025-08-18T21:31:10.000000Z',
                        'updated_at' => '2025-08-18T21:31:10.000000Z',
                        'finca' => [
                            'id_Finca' => 16,
                            'id_Propietario' => 6,
                            'Nombre' => 'Finca La Romería',
                            'Explotacion_Tipo' => 'Bovinos',
                            'archivado' => false,
                            'created_at' => '2025-08-18T00:59:56.000000Z',
                            'updated_at' => '2025-08-18T00:59:56.000000Z',
                            'propietario' => [
                                'id' => 6,
                                'id_Personal' => 17873216,
                                'Nombre' => 'Leonel',
                                'Apellido' => 'Romero',
                                'Telefono' => '04140659739',
                                'archivado' => false,
                            ],
                        ],
                        'animales' => [
                            [
                                'id_Animal' => 15,
                                'id_Rebano' => 7,
                                'Nombre' => 'Vaca Luna',
                                'codigo_animal' => 'ANIMAL-003',
                                'Sexo' => 'F',
                                'fecha_nacimiento' => '2025-01-15T00:00:00.000000Z',
                                'Procedencia' => 'Local',
                                'archivado' => false,
                            ],
                        ],
                    ],
                ],
                'first_page_url' => 'http://localhost:8000/api/rebanos?page=1',
                'from' => 1,
                'last_page' => 1,
                'last_page_url' => 'http://localhost:8000/api/rebanos?page=1',
                'next_page_url' => null,
                'path' => 'http://localhost:8000/api/rebanos',
                'per_page' => 15,
                'prev_page_url' => null,
                'to' => 2,
                'total' => 2,
            ],
        ];
    }
}
