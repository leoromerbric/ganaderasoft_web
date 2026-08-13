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
     * Crea un nuevo registro de rebaño.
     *
     * @param array $data Datos del rebaño a crear.
     * @return array Respuesta de la API indicando el resultado de la creación.
     */
    public function createRebano(array $data): array
    {
        return $this->post('/rebanos', $data);
    }

    /**
     * Actualiza la información de un rebaño existente.
     *
     * @param int $id Identificador único del rebaño a actualizar.
     * @param array $data Datos actualizados del rebaño.
     * @return array Respuesta de la API indicando el resultado de la actualización.
     */
    public function updateRebano(int $id, array $data): array
    {
        return $this->put("/rebanos/{$id}", $data);
    }
}
