<?php

namespace App\Services\Api;

use App\Services\Contracts\RegistroCeloServiceInterface;

class ApiRegistroCeloService extends BaseApiService implements RegistroCeloServiceInterface
{
    /**
     * Obtiene el listado de registros de celo con filtros opcionales.
     *
     * @param int|null $animalId
     * @param string|null $fechaInicio
     * @param string|null $fechaFin
     * @return array
     */
    public function getList(?int $animalId = null, ?string $fechaInicio = null, ?string $fechaFin = null): array
    {
        $params = [
            'animal_id'    => $animalId,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin'    => $fechaFin,
        ];

        return $this->get('/registro-celo' . $this->buildQuery($params, true));
    }

    /**
     * Obtiene el detalle de un registro de celo por su ID.
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        return $this->get("/registro-celo/{$id}");
    }

    /**
     * Registra un nuevo celo detectado.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->post('/registro-celo', $data);
    }

    /**
     * Actualiza un registro de celo existente.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update(int $id, array $data): array
    {
        return $this->put("/registro-celo/{$id}", $data);
    }

    /**
     * Elimina un registro de celo por su ID.
     *
     * @param int $id
     * @return array
     */
    public function eliminar(int $id): array
    {
        return $this->delete("/registro-celo/{$id}");
    }

    /**
     * Obtiene el listado de animales para selectores.
     *
     * @return array
     */
    public function getAnimales(): array
    {
        $response = $this->get('/animales' . $this->buildQuery([], true));
        return $this->extractCollection($response);
    }
}
