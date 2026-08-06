<?php

namespace App\Services\Api;

use App\Services\Contracts\RegistroCeloServiceInterface;

class ApiRegistroCeloService extends BaseApiService implements RegistroCeloServiceInterface
{
    private function authHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . (session('user')['token'] ?? ''),
            'X-Api-Version' => '2',
        ];
    }

    public function getList(?int $animalId = null, ?string $fechaInicio = null, ?string $fechaFin = null): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];

        $params = array_filter([
            'animal_id'   => $animalId,
            'fecha_inicio'=> $fechaInicio,
            'fecha_fin'   => $fechaFin,
        ]);
        $endpoint = '/registro-celo?nopaginate=true' . (!empty($params) ? '&' . http_build_query($params) : '');
        return $this->get($endpoint, $this->authHeaders());
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/registro-celo/{$id}", $this->authHeaders());
    }

    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/registro-celo', $data, $this->authHeaders() + ['Content-Type' => 'application/json']);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/registro-celo/{$id}", $data, $this->authHeaders() + ['Content-Type' => 'application/json']);
    }

    public function eliminar(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->delete("/registro-celo/{$id}", $this->authHeaders());
    }

    public function getAnimales(): array
    {
        if (!session('user.token')) return [];
        $response = $this->get('/animales?nopaginate=true', $this->authHeaders());
        $data = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        return (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) ? $data['data'] : $data;
    }
}
