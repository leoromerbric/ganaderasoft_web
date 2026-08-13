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
     * Obtiene los datos de una finca en específico por su ID.
     *
     * @param int $id Identificador único de la finca.
     * @return array Respuesta de la API con los datos de la finca.
     */
    public function getFinca(int $id): array
    {
        return $this->get("/fincas/{$id}");
    }

    /**
     * Crea un nuevo registro de finca.
     *
     * @param array $data Datos de la finca a crear.
     * @return array Respuesta de la API indicando el resultado de la creación.
     */
    public function createFinca(array $data): array
    {
        return $this->post('/fincas', $data);
    }

    /**
     * Actualiza la información de una finca existente.
     *
     * @param int $id Identificador único de la finca a actualizar.
     * @param array $data Datos actualizados de la finca.
     * @return array Respuesta de la API indicando el resultado de la actualización.
     */
    public function updateFinca(int $id, array $data): array
    {
        return $this->put("/fincas/{$id}", $data);
    }
}
