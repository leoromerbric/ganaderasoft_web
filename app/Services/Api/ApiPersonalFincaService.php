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
    public function getPersonalFinca(?int $fincaId = null): array
    {
        return $this->get('/personal-finca' . $this->buildQuery(['finca_id' => $fincaId], true));
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
     * Obtiene el catálogo de tipos de trabajador.
     *
     * @return array
     */
    public function getTiposTrabajador(): array
    {
        return $this->get('/tipos-trabajador' . $this->buildQuery([], true));
    }
}