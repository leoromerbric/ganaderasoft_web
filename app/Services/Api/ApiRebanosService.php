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
     * Obtiene los datos de un rebaño específico por su ID.
     *
     * @param int $id
     * @return array
     */
    public function getRebano(int $id): array
    {
        return $this->get("/rebanos/{$id}");
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

    /**
     * Archiva un rebaño activo.
     *
     * @param int $id
     * @return array
     */
    public function archiveRebano(int $id): array
    {
        return $this->post("/rebanos/{$id}/archivar");
    }

    /**
     * Desarchiva un rebaño archivado.
     *
     * @param int $id
     * @return array
     */
    public function unarchiveRebano(int $id): array
    {
        return $this->post("/rebanos/{$id}/desarchivar");
    }

    /**
     * Elimina definitivamente un rebaño y sus animales en cascada.
     *
     * @param int $id
     * @return array
     */
    public function deleteRebano(int $id): array
    {
        return $this->delete("/rebanos/{$id}");
    }
}

