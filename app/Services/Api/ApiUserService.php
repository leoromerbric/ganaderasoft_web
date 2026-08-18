<?php

namespace App\Services\Api;

use App\Services\Contracts\UserServiceInterface;

class ApiUserService extends BaseApiService implements UserServiceInterface
{
    protected string $endpoint = '/users';

    // Obtener lista de usuarios desde la API con filtros opcionales
    public function getUsers(array $filters = []): array
    {
        $params = array_filter([
            'search'     => $filters['search'] ?? null,
            'role'       => $filters['role'] ?? null,
            'status'     => $filters['status'] ?? null,
            'nopaginate' => true,
        ]);

        $query = '?' . http_build_query($params);
        $response = $this->get($this->endpoint . $query);

        return $response['data'] ?? [];
    }

    // Obtener detalle de un usuario por su ID
    public function getUserById(int $id): array
    {
        return $this->get("{$this->endpoint}/{$id}");
    }

    // Crear un nuevo usuario en el sistema
    public function createUser(array $data): array
    {
        return $this->post($this->endpoint, $data);
    }

    // Actualizar datos de un usuario existente
    public function updateUser(int $id, array $data): array
    {
        return $this->put("{$this->endpoint}/{$id}", $data);
    }

    // Alternar estado de la cuenta (activar o suspender)
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
