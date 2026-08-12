<?php

namespace App\Services\Api;

use App\Services\Contracts\DosisServiceInterface;

class ApiDosisService extends BaseApiService implements DosisServiceInterface
{
    public function getList(?int $vacunaId = null, ?bool $vigentes = null): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];

        $params = array_filter([
            'vacuna_id' => $vacunaId,
            'vigentes' => $vigentes ? 1 : null,
        ]);

        $endpoint = '/dosis' . (!empty($params) ? '?' . http_build_query($params) : '');
        return $this->get($endpoint);
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/dosis/{$id}");
    }

    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/dosis', $data);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/dosis/{$id}", $data) : [];
    }
}