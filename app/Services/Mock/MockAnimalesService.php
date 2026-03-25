<?php

namespace App\Services\Mock;

use App\Services\Contracts\AnimalesServiceInterface;

class MockAnimalesService implements AnimalesServiceInterface
{
    /**
     * Get list of animals for authenticated user
     */
    public function getAnimales(?int $rebanoId = null): array
    {
        // DEBUG: Log what we received
        \Log::debug("MockAnimalesService::getAnimales - Received rebanoId: " . var_export($rebanoId, true));
        
        $allAnimales = [
            // Animals for Rebaño 6: "Mi Rebaño" 
            [
                'id_Animal' => 13,
                'id_Rebano' => 6,
                'Nombre' => 'Vaca Bella',
                'codigo_animal' => 'ANIMAL-001',
                'Sexo' => 'F',
                'fecha_nacimiento' => '2025-03-15T00:00:00.000000Z',
                'Procedencia' => 'Finca Origen',
                'archivado' => false,
                'created_at' => '2025-08-18T21:35:24.000000Z',
                'updated_at' => '2025-08-18T21:35:24.000000Z',
                'fk_composicion_raza' => 70,
                'rebano' => [
                    'id_Rebano' => 6,
                    'id_Finca' => 15,
                    'Nombre' => 'Mi Rebaño',
                    'archivado' => false,
                ],
                'composicion_raza' => [
                    'id_Composicion' => 70,
                    'Nombre' => 'Shorthorn',
                    'Siglas' => 'SHO',
                ],
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
                'created_at' => '2025-08-18T21:36:06.000000Z',
                'updated_at' => '2025-08-18T21:36:06.000000Z',
                'fk_composicion_raza' => 71,
                'rebano' => [
                    'id_Rebano' => 6,
                    'id_Finca' => 15,
                    'Nombre' => 'Mi Rebaño',
                    'archivado' => false,
                ],
                'composicion_raza' => [
                    'id_Composicion' => 71,
                    'Nombre' => 'Hereford',
                    'Siglas' => 'HER',
                ],
            ],
            // Animals for Rebaño 7: "Rebaño Norteño"
            [
                'id_Animal' => 15,
                'id_Rebano' => 7,
                'Nombre' => 'Vaca Luna',
                'codigo_animal' => 'ANIMAL-003',
                'Sexo' => 'F',
                'fecha_nacimiento' => '2025-01-15T00:00:00.000000Z',
                'Procedencia' => 'Local',
                'archivado' => false,
                'created_at' => '2025-08-18T21:37:00.000000Z',
                'updated_at' => '2025-08-18T21:37:00.000000Z',
                'fk_composicion_raza' => 70,
                'rebano' => [
                    'id_Rebano' => 7,
                    'id_Finca' => 16,
                    'Nombre' => 'Rebaño Norteño',
                    'archivado' => false,
                ],
                'composicion_raza' => [
                    'id_Composicion' => 70,
                    'Nombre' => 'Shorthorn',
                    'Siglas' => 'SHO',
                ],
            ],
            [
                'id_Animal' => 16,
                'id_Rebano' => 7,
                'Nombre' => 'Toro Norte',
                'codigo_animal' => 'ANIMAL-004',
                'Sexo' => 'M',
                'fecha_nacimiento' => '2024-06-20T00:00:00.000000Z',
                'Procedencia' => 'Local',
                'archivado' => false,
                'created_at' => '2025-08-18T21:38:00.000000Z',
                'updated_at' => '2025-08-18T21:38:00.000000Z',
                'fk_composicion_raza' => 71,
                'rebano' => [
                    'id_Rebano' => 7,
                    'id_Finca' => 16,
                    'Nombre' => 'Rebaño Norteño',
                    'archivado' => false,
                ],
                'composicion_raza' => [
                    'id_Composicion' => 71,
                    'Nombre' => 'Hereford',
                    'Siglas' => 'HER',
                ],
            ],
            // Animals for Rebaño 8: "Rebaño Planicie"
            [
                'id_Animal' => 17,
                'id_Rebano' => 8,
                'Nombre' => 'Vaca Planicie',
                'codigo_animal' => 'ANIMAL-005',
                'Sexo' => 'F',
                'fecha_nacimiento' => '2024-08-10T00:00:00.000000Z',
                'Procedencia' => 'Compra',
                'archivado' => false,
                'created_at' => '2025-08-18T21:39:00.000000Z',
                'updated_at' => '2025-08-18T21:39:00.000000Z',
                'fk_composicion_raza' => 70,
                'rebano' => [
                    'id_Rebano' => 8,
                    'id_Finca' => 17,
                    'Nombre' => 'Rebaño Planicie',
                    'archivado' => false,
                ],
                'composicion_raza' => [
                    'id_Composicion' => 70,
                    'Nombre' => 'Shorthorn',
                    'Siglas' => 'SHO',
                ],
            ],
            [
                'id_Animal' => 18,
                'id_Rebano' => 8,
                'Nombre' => 'Toro Planicie',
                'codigo_animal' => 'ANIMAL-006',
                'Sexo' => 'M',
                'fecha_nacimiento' => '2023-12-05T00:00:00.000000Z',
                'Procedencia' => 'Compra',
                'archivado' => false,
                'created_at' => '2025-08-18T21:40:00.000000Z',
                'updated_at' => '2025-08-18T21:40:00.000000Z',
                'fk_composicion_raza' => 71,
                'rebano' => [
                    'id_Rebano' => 8,
                    'id_Finca' => 17,
                    'Nombre' => 'Rebaño Planicie',
                    'archivado' => false,
                ],
                'composicion_raza' => [
                    'id_Composicion' => 71,
                    'Nombre' => 'Hereford',
                    'Siglas' => 'HER',
                ],
            ],
        ];

        // Filter by rebano if specified
        if ($rebanoId !== null) {
            \Log::debug("MockAnimalesService::getAnimales - Filtering by rebanoId: " . $rebanoId);
            \Log::debug("MockAnimalesService::getAnimales - Before filter, total animals: " . count($allAnimales));
            
            $allAnimales = array_filter($allAnimales, function($animal) use ($rebanoId) {
                $animalRebanoId = (int) $animal['id_Rebano'];
                $filterRebanoId = (int) $rebanoId;
                $matches = $animalRebanoId === $filterRebanoId;
                \Log::debug("MockAnimalesService::getAnimales - Comparing animal rebano {$animalRebanoId} with filter {$filterRebanoId}: " . ($matches ? 'MATCH' : 'NO MATCH'));
                return $matches;
            });
            $allAnimales = array_values($allAnimales); // Re-index
            
            \Log::debug("MockAnimalesService::getAnimales - After filter, total animals: " . count($allAnimales));
        } else {
            \Log::debug("MockAnimalesService::getAnimales - No filter applied, returning all animals: " . count($allAnimales));
        }

        return [
            'success' => true,
            'message' => 'Lista de animales',
            'data' => [
                'current_page' => 1,
                'data' => $allAnimales,
                'first_page_url' => 'http://localhost:8000/api/animales?page=1',
                'from' => 1,
                'last_page' => 1,
                'last_page_url' => 'http://localhost:8000/api/animales?page=1',
                'next_page_url' => null,
                'path' => 'http://localhost:8000/api/animales',
                'per_page' => 15,
                'prev_page_url' => null,
                'to' => count($allAnimales),
                'total' => count($allAnimales),
            ],
        ];
    }

    /**
     * Get a single animal by ID
     */
    public function getAnimal(int $id): array
    {
        $allAnimales = $this->getAnimales();
        $animales = $allAnimales['data']['data'];

        foreach ($animales as $animal) {
            if ($animal['id_Animal'] == $id) {
                return [
                    'success' => true,
                    'message' => 'Animal encontrado',
                    'data' => $animal,
                ];
            }
        }

        return [
            'success' => false,
            'message' => 'Animal no encontrado',
        ];
    }

    /**
     * Create a new animal
     */
    public function createAnimal(array $data): array
    {
        return [
            'success' => true,
            'message' => 'Animal creado exitosamente',
            'data' => array_merge($data, [
                'id_Animal' => rand(100, 999),
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString(),
            ]),
        ];
    }

    /**
     * Update an existing animal
     */
    public function updateAnimal(int $id, array $data): array
    {
        return [
            'success' => true,
            'message' => 'Animal actualizado exitosamente',
            'data' => array_merge($data, [
                'id_Animal' => $id,
                'updated_at' => now()->toISOString(),
            ]),
        ];
    }

    /**
     * Get list of available animal breeds
     */
    public function getRazas(): array
    {
        return [
            'success' => true,
            'message' => 'Lista de razas', 
            'data' => [
                [
                    'id_Composicion' => 70,
                    'Nombre' => 'Shorthorn',
                    'Siglas' => 'SHO',
                    'Pelaje' => 'Rojo-Blanco',
                    'Proposito' => 'Doble',
                ],
                [
                    'id_Composicion' => 71,
                    'Nombre' => 'Hereford',
                    'Siglas' => 'HER',
                    'Pelaje' => 'Colorado Bayo con manchas blancas',
                    'Proposito' => 'Carne',
                ],
            ],
        ];
    }

    /**
     * Get list of available health states
     */
    public function getEstadosSalud(): array
    {
        return [
            'success' => true,
            'message' => 'Lista de estados de salud',
            'data' => [
                'data' => [
                    ['estado_id' => 1, 'estado_nombre' => 'Saludable'],
                    ['estado_id' => 2, 'estado_nombre' => 'Enfermo'],
                    ['estado_id' => 3, 'estado_nombre' => 'En tratamiento'],
                    ['estado_id' => 4, 'estado_nombre' => 'En observación'],
                ],
            ],
        ];
    }

    /**
     * Get list of available animal stages
     */
    public function getEtapas(): array
    {
        return [
            'success' => true,
            'message' => 'Lista de etapas',
            'data' => [
                ['etapa_id' => 1, 'etapa_nombre' => 'Cría'],
                ['etapa_id' => 2, 'etapa_nombre' => 'Desarrollo'],
                ['etapa_id' => 3, 'etapa_nombre' => 'Adulto'],
                ['etapa_id' => 4, 'etapa_nombre' => 'Reproducción'],
            ],
        ];
    }
}