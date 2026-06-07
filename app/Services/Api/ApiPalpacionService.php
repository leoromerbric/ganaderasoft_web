<?php

namespace App\Services\Api;

use App\Services\Contracts\PalpacionServiceInterface;

class ApiPalpacionService extends BaseApiService implements PalpacionServiceInterface
{
    private function authHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . (session('user')['token'] ?? ''),
        ];
    }

    public function getList(?int $animalId = null, ?string $tipo = null, ?string $fechaInicio = null, ?string $fechaFin = null): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        $params = array_filter(['animal_id' => $animalId, 'tipo' => $tipo, 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]);
        $endpoint = '/palpacion' . (!empty($params) ? '?' . http_build_query($params) : '');
        return $this->get($endpoint, $this->authHeaders());
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/palpacion/{$id}", $this->authHeaders());
    }

    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/palpacion', $data, $this->authHeaders() + ['Content-Type' => 'application/json']);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/palpacion/{$id}", $data, $this->authHeaders() + ['Content-Type' => 'application/json']);
    }

    public function eliminar(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->delete("/palpacion/{$id}", $this->authHeaders());
    }

    public function getAnimales(): array
    {
        if (!session('user.token')) return [];
        $r = $this->get('/animales', $this->authHeaders());
        return ($r['success'] ?? false) ? ($r['data']['data'] ?? $r['data'] ?? []) : [];
    }

    public function getPersonalFinca(): array
    {
        if (!session('user.token')) return [];
        $r = $this->get('/personal-finca', $this->authHeaders());
        return ($r['success'] ?? false) ? ($r['data']['data'] ?? $r['data'] ?? []) : [];
    }
}
