<?php

namespace App\Services\Api;

use App\Services\Contracts\PersonalServiceInterface;

class ApiPersonalService extends BaseApiService implements PersonalServiceInterface
{
    /**
     * Versión de API V2 para el módulo de Personal (Patrón Estrangulador)
     */
    protected string $apiVersion = '2';

    /**
     * Get list of personal for a given finca
     */
    public function getPersonal(int $idFinca): array
    {
        return $this->get('/personal-finca?finca_id=' . $idFinca);
    }

    /**
     * Create new personal for a finca
     */
    public function createPersonal(array $data): array
    {
        return $this->post('/personal-finca', $data);
    }

    /**
     * Update existing personal
     */
    public function updatePersonal(int $id, array $data): array
    {
        return $this->put('/personal-finca/' . $id, $data);
    }

    /**
     * Get list of tipos de trabajador
     */
    public function getTiposTrabajador(): array
    {
        return $this->get('/tipos-trabajador?nopaginate=true');
    }
}
