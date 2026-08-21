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
     * Alterna el estado de la cuenta (activar o suspender).
     *
     * @param int $id
     * @return array
     */
    public function toggleUserStatus(int $id): array
    {
        $userResult = $this->getUserById($id);
        if (!($userResult['success'] ?? true)) {
            return $userResult;
        }

        $user = $userResult['data'] ?? [];
        $currentStatus = strtolower($user['status'] ?? 'active');
        $isSuspended = in_array($currentStatus, ['suspended', 'inactive', 'suspendido', 'inactivo'], true);

        $action = $isSuspended ? 'enable' : 'disable';

        return $this->patch("{$this->endpoint}/{$id}/{$action}");
    }
}
