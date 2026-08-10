<?php

namespace App\Services\Api;

use App\Services\Contracts\FincasServiceInterface;

class ApiFincasService extends BaseApiService implements FincasServiceInterface
{
    /**
     * Versión de API V2 para el módulo de Fincas (Patrón Estrangulador)
     */
    protected string $apiVersion = '2';

    /**
     * Get list of fincas for authenticated user
     */
    public function getFincas(): array
    {
        return $this->get('/fincas');
    }

    /**
     * Get a single finca by ID
     */
    public function getFinca(int $id): array
    {
        return $this->get("/fincas/{$id}");
    }

    /**
     * Create a new finca
     */
    public function createFinca(array $data): array
    {
        return $this->post('/fincas', $data);
    }

    /**
     * Update an existing finca
     */
    public function updateFinca(int $id, array $data): array
    {
        return $this->put("/fincas/{$id}", $data);
    }
}
