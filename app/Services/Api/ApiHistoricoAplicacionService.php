<?php

namespace App\Services\Api;

use App\Services\Contracts\HistoricoAplicacionServiceInterface;

class ApiHistoricoAplicacionService extends BaseApiService implements HistoricoAplicacionServiceInterface
{
    private function authHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . (session('user')['token'] ?? ''),
        ];
    }

    public function getList(?int $vacunaId = null, ?string $fechaInicio = null, ?string $fechaFin = null): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        $params = array_filter(['vacuna_id' => $vacunaId, 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]);
        $endpoint = '/historico-aplicacion' . (!empty($params) ? '?' . http_build_query($params) : '');
        return $this->get($endpoint, $this->authHeaders());
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/historico-aplicacion/{$id}", $this->authHeaders());
    }

    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/historico-aplicacion', $data, $this->authHeaders() + ['Content-Type' => 'application/json']);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/historico-aplicacion/{$id}", $data, $this->authHeaders() + ['Content-Type' => 'application/json']);
    }

    public function eliminar(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->delete("/historico-aplicacion/{$id}", $this->authHeaders());
    }

    public function getVacunas(): array
    {
        if (!session('user.token')) return [];
        $r = $this->get('/vacunas', $this->authHeaders());
        return ($r['success'] ?? false) ? ($r['data'] ?? []) : [];
    }

    public function getCasasComerciales(): array
    {
        if (!session('user.token')) return [];
        $r = $this->get('/casas-comerciales', $this->authHeaders());
        return ($r['success'] ?? false) ? ($r['data'] ?? []) : [];
    }

    public function getDosis(): array
    {
        if (!session('user.token')) return [];
        $r = $this->get('/dosis', $this->authHeaders());
        return ($r['success'] ?? false) ? ($r['data'] ?? []) : [];
    }
}
