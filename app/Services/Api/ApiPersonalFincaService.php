<?php

namespace App\Services\Api;

use App\Services\Contracts\PersonalFincaServiceInterface;

class ApiPersonalFincaService extends BaseApiService implements PersonalFincaServiceInterface
{
    /**
     * Obtiene el listado de personal de finca con filtro opcional por finca.
     *
     * @param int|null $fincaId
     * @return array
     */
    public function getPersonalFinca(array|int|null $filters = null): array
    {
        $params = is_array($filters) ? $filters : (is_numeric($filters) ? ['finca_id' => (int)$filters] : []);
        return $this->get('/personal-finca' . $this->buildQuery($params, true));
    }

    /**
     * Obtiene un registro individual de personal de finca por ID.
     *
     * @param int $id
     * @return array
     */
    public function getPersonalFincaById(int $id): array
    {
        return $this->get("/personal-finca/{$id}");
    }

    /**
     * Asigna un nuevo trabajador a una finca.
     *
     * @param array $data
     * @return array
     */
    public function createPersonalFinca(array $data): array
    {
        return $this->post('/personal-finca', $data);
    }

    /**
     * Actualiza la asignación de personal de finca.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updatePersonalFinca(int $id, array $data): array
    {
        return $this->put("/personal-finca/{$id}", $data);
    }

    /**
     * Elimina una asignación de personal de finca.
     *
     * @param int $id
     * @return array
     */
    public function deletePersonalFinca(int $id): array
    {
        return $this->delete("/personal-finca/{$id}");
    }

    /**
     * Enable personal de finca (status = true).
     *
     * @param int $id
     * @return array
     */
    public function enable(int $id): array
    {
        return $this->patch("/personal-finca/{$id}/enable");
    }

    /**
     * Disable personal de finca (status = false).
     *
     * @param int $id
     * @return array
     */
    public function disable(int $id): array
    {
        return $this->patch("/personal-finca/{$id}/disable");
    }

    /**
     * Obtiene el catálogo de tipos de trabajador.
     *
     * @return array
     */
    public function getTiposTrabajador(): array
    {
        return $this->get('/tipos-trabajador' . $this->buildQuery([], true));
    }
}