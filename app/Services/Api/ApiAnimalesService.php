<?php

namespace App\Services\Api;

use App\Services\Contracts\AnimalesServiceInterface;

class ApiAnimalesService extends BaseApiService implements AnimalesServiceInterface
{
    /**
     * Obtiene la lista de animales para el usuario autenticado.
     * Permite filtrar por rebaño y solicita resultados sin paginar.
     *
     * @param int|null $rebanoId ID del rebaño para filtrar (opcional).
     * @return array Respuesta de la API con el listado de animales.
     */
    public function getAnimales(?int $rebanoId = null): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        $params = ['nopaginate' => 'true'];
        
        if ($rebanoId) {
            $params['rebano_id'] = $rebanoId;
        }

        $endpoint = '/animales?' . http_build_query($params);

        return $this->get($endpoint);
    }

    /**
     * Obtiene el detalle de un animal específico mediante su ID.
     *
     * @param int $id Identificador del animal.
     * @return array Respuesta de la API con los datos del animal.
     */
    public function getAnimal(int $id): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->get("/animales/{$id}");
    }

    /**
     * Crea un nuevo registro de animal.
     *
     * @param array $data Datos del animal a crear.
     * @return array Respuesta de la API indicando el resultado de la creación.
     */
    public function createAnimal(array $data): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->post('/animales', $data);
    }

    /**
     * Actualiza la información de un animal existente.
     *
     * @param int $id Identificador del animal a actualizar.
     * @param array $data Nuevos datos para el animal.
     * @return array Respuesta de la API indicando el resultado de la actualización.
     */
    public function updateAnimal(int $id, array $data): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->put("/animales/{$id}", $data);
    }

    /**
     * Obtiene el catálogo de composiciones de razas disponibles.
     *
     * @return array Respuesta de la API con el listado de razas.
     */
    public function getRazas(): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->get('/composicion-raza');
    }

    /**
     * Obtiene el catálogo de estados de salud disponibles.
     *
     * @return array Respuesta de la API con el listado de estados de salud.
     */
    public function getEstadosSalud(): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->get('/estados-salud');
    }

    /**
     * Obtiene el catálogo de etapas de crecimiento/producción disponibles.
     *
     * @return array Respuesta de la API con el listado de etapas.
     */
    public function getEtapas(): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->get('/etapas');
    }
}
