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
                        'Nombre' => 'Mi Rebaño',
                        'archivado' => false,
                        'created_at' => '2025-08-18T21:30:56.000000Z',
                        'updated_at' => '2025-08-18T21:30:56.000000Z',
                    ],
                    [
                        'id_Rebano' => 7,
                        'id_Finca' => 16,
                        'Nombre' => 'Rebaño Norteño',
                        'archivado' => false,
                        'created_at' => '2025-08-18T21:31:10.000000Z',
                        'updated_at' => '2025-08-18T21:31:10.000000Z',
                    ],
                    [
                        'id_Rebano' => 8,
                        'id_Finca' => 17,
                        'Nombre' => 'Rebaño Planicie',
                        'archivado' => false,
                        'created_at' => '2025-08-18T21:32:00.000000Z',
                        'updated_at' => '2025-08-18T21:32:00.000000Z',
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
                'to' => 3,
                'total' => 3,
            ],
        ];
    }

    /**
     * Create a new rebaño
     */
    public function createRebano(array $data): array
    {
        return [
            'success' => true,
            'message' => 'Rebaño creado exitosamente',
            'data' => array_merge($data, [
                'id_Rebano' => rand(100, 999),
                'archivado' => false,
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString(),
            ]),
        ];
    }

    /**
     * Update an existing rebaño
     */
    public function updateRebano(int $id, array $data): array
    {
        return [
            'success' => true,
            'message' => 'Rebaño actualizado exitosamente',
            'data' => array_merge($data, [
                'id_Rebano' => $id,
                'updated_at' => now()->toISOString(),
            ]),
        ];
    }
}
