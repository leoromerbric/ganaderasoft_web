<?php

namespace App\Services\Api;

use App\Services\Contracts\RebanosServiceInterface;

class ApiRebanosService extends BaseApiService implements RebanosServiceInterface
{
    /**
     * Obtiene la lista de rebaños del usuario autenticado.
     *
     * @param array $params
     * @return array
     */
    public function getRebanos(array $params = []): array
    {
        return $this->get('/rebanos' . $this->buildQuery($params, true));
    }

    /**
     * Crea un nuevo registro de rebaño.
     *
     * @param array $data
     * @return array
     */
    public function createRebano(array $data): array
    {
        return $this->post('/rebanos', $data);
    }

    /**
     * Actualiza la información de un rebaño existente.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateRebano(int $id, array $data): array
    {
        return $this->put("/rebanos/{$id}", $data);
    }
}
