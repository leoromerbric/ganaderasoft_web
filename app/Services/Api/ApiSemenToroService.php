<?php

namespace App\Services\Api;

use App\Services\Contracts\SemenToroServiceInterface;

class ApiSemenToroService extends BaseApiService implements SemenToroServiceInterface
{
    private function authHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . (session('user')['token'] ?? ''),
            'X-Api-Version' => '2',
        ];
    }

    public function getList(?int $toroId = null, ?bool $activo = null): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        $params = array_filter([
            'toro_id' => $toroId, 
            'activo' => $activo !== null ? ($activo ? '1' : '0') : null,
            'nopaginate' => 'true'
        ]);
        $endpoint = '/semen-toro' . (!empty($params) ? '?' . http_build_query($params) : '');
        return $this->get($endpoint, $this->authHeaders());
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/semen-toro/{$id}", $this->authHeaders());
    }

    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/semen-toro', $data, $this->authHeaders() + ['Content-Type' => 'application/json']);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/semen-toro/{$id}", $data, $this->authHeaders() + ['Content-Type' => 'application/json']);
    }

    public function eliminar(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->delete("/semen-toro/{$id}", $this->authHeaders());
    }

    public function getToros(): array
    {
        if (!session('user.token')) return [];
        $r = $this->get('/animales?sexo=M&nopaginate=true', $this->authHeaders());
        return ($r['success'] ?? false) ? ($r['data']['data'] ?? $r['data'] ?? []) : [];
    }
}
