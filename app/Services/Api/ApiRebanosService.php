<?php

namespace App\Services\Api;

use App\Services\Contracts\RebanosServiceInterface;

class ApiRebanosService extends BaseApiService implements RebanosServiceInterface
{
    /**
     * Versión de API V2 para el módulo de Rebaños (Patrón Estrangulador)
     */
    protected string $apiVersion = '2';

    /**
     * Get list of rebaños for authenticated user
     */
    public function getRebanos(): array
    {
        return $this->get('/rebanos');
    }

    /**
     * Create a new rebaño
     */
    public function createRebano(array $data): array
    {
        return $this->post('/rebanos', $data);
    }

    /**
     * Update an existing rebaño
     */
    public function updateRebano(int $id, array $data): array
    {
        return $this->put("/rebanos/{$id}", $data);
    }
}
