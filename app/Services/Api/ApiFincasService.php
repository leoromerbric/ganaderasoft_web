<?php

namespace App\Services\Api;

use App\Services\Contracts\FincasServiceInterface;

class ApiFincasService extends BaseApiService implements FincasServiceInterface
{
    /**
     * Obtiene la lista de fincas del usuario autenticado.
     * 
     * @return array Respuesta de la API con la colección de fincas.
     */
    public function getFincas(): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->get('/fincas', ['nopaginate' => 'true']);
    }

    /**
     * Obtiene los datos de una finca en específico por su ID.
     *
     * @param int $id Identificador único de la finca.
     * @return array Respuesta de la API con los datos de la finca.
     */
    public function getFinca(int $id): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

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
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

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
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->put("/fincas/{$id}", $data);
    }
}
