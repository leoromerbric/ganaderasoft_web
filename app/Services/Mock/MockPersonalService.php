<?php

namespace App\Services\Mock;

use App\Services\Contracts\PersonalServiceInterface;

class MockPersonalService implements PersonalServiceInterface
{
    /**
     * Get list of personal for a given finca (mock data based on API response structure)
     */
    public function getPersonal(int $idFinca): array
    {
        return [
            'success' => true,
            'message' => 'Lista de personal de finca obtenida exitosamente',
            'data' => [
                [
                    'id_Tecnico' => 1004,
                    'id_Finca' => $idFinca,
                    'Cedula' => 12345678,
                    'Nombre' => 'Juan',
                    'Apellido' => 'Pérez',
                    'Telefono' => '3001234567',
                    'Correo' => 'juan.perez@email.com',
                    'Tipo_Trabajador' => 'Tecnico',
                    'created_at' => '2025-08-24T04:01:26.000000Z',
                    'updated_at' => '2025-08-24T04:01:26.000000Z',
                    'finca' => [
                        'id_Finca' => $idFinca,
                        'id_Propietario' => 6,
                        'Nombre' => 'Finca Demo',
                        'Explotacion_Tipo' => 'Bovinos',
                        'archivado' => false,
                        'created_at' => '2025-08-18T00:59:56.000000Z',
                        'updated_at' => '2025-08-18T00:59:56.000000Z',
                    ],
                ],
                [
                    'id_Tecnico' => 1005,
                    'id_Finca' => $idFinca,
                    'Cedula' => 12345644,
                    'Nombre' => 'Martin',
                    'Apellido' => 'González',
                    'Telefono' => '300123423457',
                    'Correo' => 'martin.gonzalez@email.com',
                    'Tipo_Trabajador' => 'Veterinario',
                    'created_at' => '2025-08-24T04:02:20.000000Z',
                    'updated_at' => '2025-08-24T04:02:20.000000Z',
                    'finca' => [
                        'id_Finca' => $idFinca,
                        'id_Propietario' => 6,
                        'Nombre' => 'Finca Demo',
                        'Explotacion_Tipo' => 'Bovinos',
                        'archivado' => false,
                        'created_at' => '2025-08-18T00:59:56.000000Z',
                        'updated_at' => '2025-08-18T00:59:56.000000Z',
                    ],
                ],
                [
                    'id_Tecnico' => 1006,
                    'id_Finca' => $idFinca,
                    'Cedula' => 12345655,
                    'Nombre' => 'Julio',
                    'Apellido' => 'Ramírez',
                    'Telefono' => '300123423457',
                    'Correo' => 'julio.ramirez@email.com',
                    'Tipo_Trabajador' => 'Vigilante',
                    'created_at' => '2025-08-24T18:54:36.000000Z',
                    'updated_at' => '2025-08-24T18:54:36.000000Z',
                    'finca' => [
                        'id_Finca' => $idFinca,
                        'id_Propietario' => 6,
                        'Nombre' => 'Finca Demo',
                        'Explotacion_Tipo' => 'Bovinos',
                        'archivado' => false,
                        'created_at' => '2025-08-18T00:59:56.000000Z',
                        'updated_at' => '2025-08-18T00:59:56.000000Z',
                    ],
                ],
            ],
            'pagination' => [
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => 15,
                'total' => 3,
            ],
        ];
    }
}
