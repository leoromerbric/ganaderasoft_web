<?php

namespace App\Services\Api;

use App\Services\Contracts\UserServiceInterface;

class ApiUserService extends BaseApiService implements UserServiceInterface
{
    protected string $endpoint = '/users';

    /**
     * Obtiene el listado de usuarios desde la API con filtros opcionales.
     *
     * @param array $filters
     * @return array
     */
    public function getUsers(array $filters = []): array
    {
        $response = $this->get($this->endpoint . $this->buildQuery($filters, true));
        return $this->extractCollection($response);
    }

    /**
     * Obtiene el detalle de un usuario por su ID.
     *
     * @param int $id
     * @return array
     */
    public function getUserById(int $id): array
    {
        return $this->get("{$this->endpoint}/{$id}");
    }

    /**
     * Registra un nuevo usuario en el sistema.
     *
     * @param array $data
     * @return array
     */
    public function createUser(array $data): array
    {
        return $this->post($this->endpoint, $data);
    }

    /**
     * Actualiza los datos de un usuario existente.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateUser(int $id, array $data): array
    {
        return $this->put("{$this->endpoint}/{$id}", $data);
    }

    /**
     * Activa (habilita) una cuenta de usuario.
     *
     * @param int $id
     * @return array
     */
    public function enableUser(int $id): array
    {
        return $this->patch("{$this->endpoint}/{$id}/enable");
    }

    /**
     * Suspende (deshabilita) una cuenta de usuario.
     *
     * @param int $id
     * @return array
     */
    public function disableUser(int $id): array
    {
        return $this->patch("{$this->endpoint}/{$id}/disable");
    }

    /**
     * Elimina un usuario del sistema.
     *
     * @param int $id
     * @return array
     */
    public function deleteUser(int $id): array
    {
        return $this->delete("{$this->endpoint}/{$id}");
    }
}
