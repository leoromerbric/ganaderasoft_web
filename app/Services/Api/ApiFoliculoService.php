<?php

namespace App\Services\Api;

use App\Services\Contracts\FoliculoServiceInterface;

class ApiFoliculoService extends BaseApiService implements FoliculoServiceInterface
{
    protected string $endpoint = '/foliculos';

    /**
     * Obtiene el listado de medidas/estados foliculares.
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
     * Obtiene el detalle de un registro folicular por su ID.
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        return $this->get("{$this->endpoint}/{$id}");
    }

    /**
     * Registra un nuevo tipo/medida folicular.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->post($this->endpoint, $data);
    }

    /**
     * Actualiza un registro folicular existente.
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
     * Elimina un registro folicular por su ID.
     *
     * @param int $id
     * @return array
     */
    public function deleteItem(int $id): array
    {
        return $this->delete("{$this->endpoint}/{$id}");
    }
}
