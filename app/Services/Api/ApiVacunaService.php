<?php

namespace App\Services\Api;

use App\Services\Contracts\VacunaServiceInterface;

class ApiVacunaService extends BaseApiService implements VacunaServiceInterface
{
    protected string $endpoint = '/vacunas';

    /**
     * Obtiene el listado de vacunas registradas en el catálogo maestro.
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
     * Obtiene el detalle de una vacuna por su ID.
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        return $this->get("{$this->endpoint}/{$id}");
    }

    /**
     * Registra una nueva vacuna en el catálogo maestro.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->post($this->endpoint, $data);
    }

    /**
     * Actualiza una vacuna existente.
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
     * Elimina una vacuna del catálogo por su ID.
     *
     * @param int $id
     * @return array
     */
    public function deleteItem(int $id): array
    {
        return $this->delete("{$this->endpoint}/{$id}");
    }
}
