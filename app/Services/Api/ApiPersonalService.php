<?php

namespace App\Services\Api;

use App\Services\Contracts\PersonalServiceInterface;

class ApiPersonalService extends BaseApiService implements PersonalServiceInterface
{
    /**
     * Obtiene la lista de personal para una finca dada.
     *
     * @param int $idFinca
     * @return array
     */
    public function getPersonal(int $idFinca): array
    {
        return $this->get('/personal-finca' . $this->buildQuery(['finca_id' => $idFinca], true));
    }

    /**
     * Registra nuevo personal para una finca.
     *
     * @param array $data
     * @return array
     */
    public function createPersonal(array $data): array
    {
        return $this->post('/personal-finca', $data);
    }

    /**
     * Actualiza un registro de personal.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updatePersonal(int $id, array $data): array
    {
        return $this->put("/personal-finca/{$id}", $data);
    }

    /**
     * Obtiene el listado de tipos de trabajador.
     *
     * @return array
     */
    public function getTiposTrabajador(): array
    {
        return $this->get('/tipos-trabajador' . $this->buildQuery([], true));
    }
}
