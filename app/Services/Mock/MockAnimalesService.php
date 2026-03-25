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
            $allAnimales = array_filter($allAnimales, function($animal) use ($rebanoId) {
                return (int) $animal['id_Rebano'] === (int) $rebanoId;
            });
            $allAnimales = array_values($allAnimales); // Re-index
        }
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
                ],
                'composicion_raza' => [
                    'id_Composicion' => 70,
                    'Nombre' => 'Shorthorn',
                    'Siglas' => 'SHO',
                    'Pelaje' => 'Rojo-Blanco',
                    'Proposito' => 'Doble',
                    'Tipo_Raza' => 'Bos Taurus',
                    'Origen' => 'Noroeste Inglaterra',
                    'Caracteristica_Especial' => 'Adaptabilidad',
                    'Proporcion_Raza' => 'Grande',
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
                ],
                'composicion_raza' => [
                    'id_Composicion' => 71,
                    'Nombre' => 'Hereford',
                    'Siglas' => 'HER',
                    'Pelaje' => 'Colorado Bayo y manchas blancas en la cabeza',
                    'Proposito' => 'Carne',
                    'Tipo_Raza' => 'Bos Taurus',
                    'Origen' => 'Suroeste Inglaterra',
                    'Caracteristica_Especial' => 'madurez precoz',
                    'Proporcion_Raza' => 'Grande',
                ],
            ],
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
                ],
                'composicion_raza' => [
                    'id_Composicion' => 70,
                    'Nombre' => 'Shorthorn',
                    'Siglas' => 'SHO',
                    'Pelaje' => 'Rojo-Blanco',
                    'Proposito' => 'Doble',
                    'Tipo_Raza' => 'Bos Taurus',
                    'Origen' => 'Noroeste Inglaterra',
                    'Caracteristica_Especial' => 'Adaptabilidad',
                    'Proporcion_Raza' => 'Grande',
                ],
            ],
        ];

        // Filter by rebano if specified
        if ($rebanoId !== null) {
            \Log::info('FILTERING: rebanoId provided: ' . $rebanoId);
            \Log::info('Available animals before filtering: ' . json_encode(array_map(function($a) { return ['id' => $a['id_Animal'], 'rebano' => $a['id_Rebano'], 'name' => $a['Nombre']]; }, $allAnimales)));
            
            $allAnimales = array_filter($allAnimales, function($animal) use ($rebanoId) {
                $animalRebano = (int) $animal['id_Rebano'];
                $filterRebano = (int) $rebanoId;
                $match = $animalRebano === $filterRebano;
                \Log::info("Animal {$animal['Nombre']} (rebano {$animalRebano}) vs filter ({$filterRebano}): " . ($match ? 'MATCH' : 'NO MATCH'));
                return $match;
            });
            $allAnimales = array_values($allAnimales); // Re-index
            
            \Log::info('Animals after filtering: ' . count($allAnimales));
        } else {
            \Log::info('NO FILTERING: rebanoId is null, showing all animals');
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
     * Get list of available breeds (composicion_raza)
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
                ],
                [
                    'id_Composicion' => 71,
                    'Nombre' => 'Hereford',
                    'Siglas' => 'HER',
                ],
                [
                    'id_Composicion' => 72,
                    'Nombre' => 'Brahman',
                    'Siglas' => 'BRA',
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
                ['estado_id' => 1, 'estado_nombre' => 'Sano'],
                ['estado_id' => 2, 'estado_nombre' => 'Enfermo'],
                ['estado_id' => 3, 'estado_nombre' => 'En Tratamiento'],
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
