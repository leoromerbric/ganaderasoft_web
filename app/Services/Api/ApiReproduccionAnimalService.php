<?php

namespace App\Services\Api;

use App\Services\Contracts\ReproduccionAnimalServiceInterface;

class ApiReproduccionAnimalService extends BaseApiService implements ReproduccionAnimalServiceInterface
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
        $endpoint = '/reproduccion-animal' . (!empty($params) ? '?' . http_build_query($params) : '');
        return $this->get($endpoint, $this->authHeaders());
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/reproduccion-animal/{$id}", $this->authHeaders());
    }

    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/reproduccion-animal', $data, $this->authHeaders() + ['Content-Type' => 'application/json']);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/reproduccion-animal/{$id}", $data, $this->authHeaders() + ['Content-Type' => 'application/json']);
    }

    public function eliminar(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->delete("/reproduccion-animal/{$id}", $this->authHeaders());
    }

    public function getAnimales(): array
    {
        if (!session('user.token')) return [];
        $r = $this->get('/animales', $this->authHeaders());
        return ($r['success'] ?? false) ? ($r['data']['data'] ?? $r['data'] ?? []) : [];
    }
}
