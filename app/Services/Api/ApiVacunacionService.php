<?php

namespace App\Services\Api;

use App\Services\Contracts\VacunacionServiceInterface;

class ApiVacunacionService extends BaseApiService implements VacunacionServiceInterface
{
    /**
     * Obtiene el listado de vacunaciones aplicando filtros opcionales.
     *
     * @param array $filters
     * @return array
     */
    public function getList(array $filters = []): array
    {
        $params = array_merge(
            ['nopaginate' => 'true'],
            array_filter($filters, fn($v) => !is_null($v) && $v !== '')
        );

        return $this->get('/vacunaciones?' . http_build_query($params));
    }

    /**
     * Obtiene el detalle de un registro de vacunación específico.
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        return $this->get("/vacunaciones/{$id}");
    }

    /**
     * Envía la solicitud para crear una o múltiples vacunaciones.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->post('/vacunaciones', $data);
    }

    /**
     * Envía la solicitud para actualizar un registro de vacunación.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update(int $id, array $data): array
    {
        return $this->put("/vacunaciones/{$id}", $data);
    }

    /**
     * Envía la solicitud para eliminar un registro de vacunación.
     *
     * @param int $id
     * @return array
     */
    public function eliminar(int $id): array
    {
        return $this->delete("/vacunaciones/{$id}");
    }
}
