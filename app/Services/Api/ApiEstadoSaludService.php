<?php

namespace App\Services\Api;

use App\Services\Contracts\EstadoSaludServiceInterface;

class ApiEstadoSaludService extends BaseApiService implements EstadoSaludServiceInterface
{
    protected string $endpoint = '/estados-salud';

    /**
     * Obtiene el listado de estados de salud.
     *
     * @param array $params
     * @return array
     */
    public function getAll(array $params = []): array
    {
        $response = $this->get($this->endpoint . $this->buildQuery($params, true));
        return $this->extractCollection($response);
    }

    /**
     * Obtiene el detalle de un estado de salud por su ID.
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        return $this->get("{$this->endpoint}/{$id}");
    }

    /**
     * Registra un nuevo estado de salud.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->post($this->endpoint, $data);
    }

    /**
     * Actualiza un estado de salud existente.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update(int $id, array $data): array
    {
        return $this->put("{$this->endpoint}/{$id}", $data);
    }

    /**
     * Elimina un estado de salud por su ID.
     *
     * @param int $id
     * @return array
     */
    public function deleteItem(int $id): array
    {
        return $this->delete("{$this->endpoint}/{$id}");
    }
}
