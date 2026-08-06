<?php

namespace App\Services\Api;

use App\Services\Contracts\DiagnosticoServiceInterface;

class ApiDiagnosticoService extends BaseApiService implements DiagnosticoServiceInterface
{
    private function authHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . (session('user')['token'] ?? ''),
            'X-Api-Version' => '2',
        ];
    }

    public function getList(?int $animalId = null, ?string $tipo = null, ?string $fechaInicio = null, ?string $fechaFin = null): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        $params = array_filter(['animal_id' => $animalId, 'tipo' => $tipo, 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]);
        $endpoint = '/diagnostico?nopaginate=true' . (!empty($params) ? '&' . http_build_query($params) : '');
        return $this->get($endpoint, $this->authHeaders());
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/diagnostico/{$id}", $this->authHeaders());
    }

    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/diagnostico', $data, $this->authHeaders() + ['Content-Type' => 'application/json']);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/diagnostico/{$id}", $data, $this->authHeaders() + ['Content-Type' => 'application/json']);
    }

    public function eliminar(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->delete("/diagnostico/{$id}", $this->authHeaders());
    }

    public function getAnimales(): array
    {
        if (!session('user.token')) return [];
        $r = $this->get('/animales?nopaginate=true', $this->authHeaders());
        $data = ($r['success'] ?? false) ? ($r['data'] ?? []) : [];
        return (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) ? $data['data'] : $data;
    }
}
