<?php

namespace App\Services\Api;

use App\Services\Contracts\ComposicionRazaServiceInterface;

class ApiComposicionRazaService extends BaseApiService implements ComposicionRazaServiceInterface
{
    protected string $endpoint = '/composicion-raza';

    /**
     * Obtiene el listado de composiciones de raza.
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
     * Obtiene el detalle de una composición de raza por su ID.
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        return $this->get("{$this->endpoint}/{$id}");
    }

    /**
     * Registra una nueva composición de raza.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->post($this->endpoint, $data);
    }

    /**
     * Actualiza una composición de raza existente.
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
     * Elimina una composición de raza por su ID.
     *
     * @param int $id
     * @return array
     */
    public function deleteItem(int $id): array
    {
        return $this->delete("{$this->endpoint}/{$id}");
    }
}
