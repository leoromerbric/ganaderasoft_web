<?php

namespace App\Services\Api;

use App\Services\Contracts\DosisServiceInterface;

class ApiDosisService extends BaseApiService implements DosisServiceInterface
{
    private function authHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . (session('user')['token'] ?? ''),
        ];
    }

    public function getList(?int $vacunaId = null, ?bool $vigentes = null): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];

        $params = array_filter([
            'vacuna_id' => $vacunaId,
            'vigentes' => $vigentes ? 1 : null,
        ]);

        $endpoint = '/dosis' . (!empty($params) ? '?' . http_build_query($params) : '');
        return $this->get($endpoint, $this->authHeaders());
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/dosis/{$id}", $this->authHeaders());
    }

    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/dosis', $data, $this->authHeaders() + ['Content-Type' => 'application/json']);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/dosis/{$id}", $data, $this->authHeaders() + ['Content-Type' => 'application/json']);
    }

    public function eliminar(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->delete("/dosis/{$id}", $this->authHeaders());
    }

    public function getVacunas(): array
    {
        if (!session('user.token')) return [];
        $response = $this->get('/vacunas', $this->authHeaders());
        return ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
    }

    public function getCasasComerciales(): array
    {
        if (!session('user.token')) return [];
        $response = $this->get('/casas-comerciales', $this->authHeaders());
        return ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
    }

    public function getAnimales(): array
    {
        if (!session('user.token')) return [];
        $response = $this->get('/animales', $this->authHeaders());
        return ($response['success'] ?? false) ? ($response['data']['data'] ?? $response['data'] ?? []) : [];
    }

    public function getRebanos(): array
    {
        if (!session('user.token')) return [];
        $response = $this->get('/rebanos', $this->authHeaders());
        return ($response['success'] ?? false) ? ($response['data']['data'] ?? $response['data'] ?? []) : [];
    }
}