<?php

namespace App\Services\Api;

use App\Services\Contracts\VacunacionServiceInterface;

class ApiVacunacionService extends BaseApiService implements VacunacionServiceInterface
{
    private function authHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . (session('user')['token'] ?? ''),
            'X-Api-Version' => '2',
        ];
    }

    public function getList(array $filters = []): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'data' => []];
        }

        $query = array_filter([
            'vacuna_id' => $filters['vacuna_id'] ?? null,
            'rebano_id' => $filters['rebano_id'] ?? null,
            'fecha_inicio' => $filters['fecha_inicio'] ?? null,
            'fecha_fin' => $filters['fecha_fin'] ?? null,
        ]);

        $endpoint = '/vacunaciones?nopaginate=true' . (!empty($query) ? '&' . http_build_query($query) : '');
        return $this->get($endpoint, $this->authHeaders());
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'data' => []];
        }

        return $this->get("/vacunaciones/{$id}", $this->authHeaders());
    }

    public function create(array $data): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->post('/vacunaciones', $data, $this->authHeaders() + ['Content-Type' => 'application/json']);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->put("/vacunaciones/{$id}", $data, $this->authHeaders() + ['Content-Type' => 'application/json']);
    }

    public function eliminar(int $id): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->delete("/vacunaciones/{$id}", $this->authHeaders());
    }

    public function getAnimalesElegibles(array $filters): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'data' => []];
        }

        $query = array_filter([
            'rebano_id' => $filters['rebano_id'] ?? null,
            'sexo' => $filters['sexo'] ?? null,
            'etapa_id' => $filters['etapa_id'] ?? null,
        ]);

        $endpoint = '/vacunaciones/animales-elegibles' . (!empty($query) ? '?' . http_build_query($query) : '');
        return $this->get($endpoint, $this->authHeaders());
    }

    public function getVacunas(): array
    {
        if (!session('user.token')) {
            return [];
        }

        $response = $this->get('/vacunas?nopaginate=true', $this->authHeaders());
        $data = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        return (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) ? $data['data'] : $data;
    }

    public function getEtapas(): array
    {
        if (!session('user.token')) {
            return [];
        }

        $response = $this->get('/etapas?nopaginate=true', $this->authHeaders());
        $data = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        return (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) ? $data['data'] : $data;
    }

    public function getRebanos(): array
    {
        if (!session('user.token')) {
            return [];
        }

        $response = $this->get('/rebanos?nopaginate=true', $this->authHeaders());
        $data = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        return (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) ? $data['data'] : $data;
    }
}
