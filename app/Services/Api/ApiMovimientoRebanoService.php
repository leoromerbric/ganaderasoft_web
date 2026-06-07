<?php

namespace App\Services\Api;

use App\Services\Contracts\MovimientoRebanoServiceInterface;

class ApiMovimientoRebanoService extends BaseApiService implements MovimientoRebanoServiceInterface
{
    private function authHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . (session('user')['token'] ?? ''),
        ];
    }

    public function getList(?int $fincaId = null, ?int $rebanoId = null): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        $params = array_filter(['id_finca' => $fincaId, 'id_rebano' => $rebanoId]);
        $endpoint = '/movimiento-rebano' . (!empty($params) ? '?' . http_build_query($params) : '');
        return $this->get($endpoint, $this->authHeaders());
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/movimiento-rebano/{$id}", $this->authHeaders());
    }

    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/movimiento-rebano', $data, $this->authHeaders() + ['Content-Type' => 'application/json']);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/movimiento-rebano/{$id}", $data, $this->authHeaders() + ['Content-Type' => 'application/json']);
    }

    public function eliminar(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->delete("/movimiento-rebano/{$id}", $this->authHeaders());
    }

    public function getFincas(): array
    {
        if (!session('user.token')) return [];
        $r = $this->get('/fincas', $this->authHeaders());
        return ($r['success'] ?? false) ? ($r['data']['data'] ?? $r['data'] ?? []) : [];
    }

    public function getRebanos(): array
    {
        if (!session('user.token')) return [];
        $r = $this->get('/rebanos', $this->authHeaders());
        return ($r['success'] ?? false) ? ($r['data']['data'] ?? $r['data'] ?? []) : [];
    }

    public function getAnimales(): array
    {
        if (!session('user.token')) return [];
        $r = $this->get('/animales', $this->authHeaders());
        return ($r['success'] ?? false) ? ($r['data']['data'] ?? $r['data'] ?? []) : [];
    }
}
