<?php

namespace App\Services\Api;

use App\Services\Contracts\RebanosServiceInterface;

class ApiRebanosService extends BaseApiService implements RebanosServiceInterface
{
    /**
     * Obtiene la lista de rebaños del usuario autenticado.
     *
     * @return array Respuesta de la API con la colección de rebaños.
     */
    public function getRebanos(): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->get('/rebanos', ['nopaginate' => 'true']);
    }

    /**
     * Crea un nuevo registro de rebaño.
     *
     * @param array $data Datos del rebaño a crear.
     * @return array Respuesta de la API indicando el resultado de la creación.
     */
    public function createRebano(array $data): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

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
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->put('/rebanos/' . $id, $data);
    }
}
