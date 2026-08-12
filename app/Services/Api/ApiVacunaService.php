<?php

namespace App\Services\Api;

use App\Services\Contracts\VacunaServiceInterface;

class ApiVacunaService extends BaseApiService implements VacunaServiceInterface
{
    public function getList(?string $nombre = null): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        $endpoint = '/vacunas?nopaginate=true' . ($nombre ? '&nombre=' . urlencode($nombre) : '');
        return $this->get($endpoint);
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/vacunas/{$id}");
    }

    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/vacunas', $data);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/vacunas/{$id}", $data);
    }

    public function eliminar(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->delete("/vacunas/{$id}");
    }
}
