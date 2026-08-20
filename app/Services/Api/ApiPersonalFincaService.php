<?php

namespace App\Services\Api;

use App\Services\Contracts\PersonalFincaServiceInterface;

class ApiPersonalFincaService extends BaseApiService implements PersonalFincaServiceInterface
{

    /**
     * Get list of personal de finca
     */
    public function getPersonalFinca(?int $fincaId = null): array
    {
        $endpoint = '/personal-finca?nopaginate=true';
        if ($fincaId) {
            $endpoint .= '&finca_id=' . $fincaId;
        }

        return $this->get($endpoint);
    }

    /**
     * Get a single personal de finca record by ID
     */
    public function getPersonalFincaById(int $id): array
    {
        return $this->get("/personal-finca/{$id}");
    }

    /**
     * Create a new personal de finca record
     */
    public function createPersonalFinca(array $data): array
    {
        return $this->post('/personal-finca', $data);
    }

    /**
     * Update an existing personal de finca record
     */
    public function updatePersonalFinca(int $id, array $data): array
    {
        return $this->put("/personal-finca/{$id}", $data);
    }

    /**
     * Delete a personal de finca record
     */
    public function deletePersonalFinca(int $id): array
    {
        return $this->delete("/personal-finca/{$id}");
    }

    /**
     * Get list of tipos de trabajador
     */
    public function getTiposTrabajador(): array
    {
        return $this->get('/tipos-trabajador?nopaginate=true');
    }
}