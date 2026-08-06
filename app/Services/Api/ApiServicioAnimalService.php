<?php

namespace App\Services\Api;

use App\Services\Contracts\ServicioAnimalServiceInterface;

class ApiServicioAnimalService extends BaseApiService implements ServicioAnimalServiceInterface
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
        $endpoint = '/servicio-animal?nopaginate=true' . (!empty($params) ? '&' . http_build_query($params) : '');
        return $this->get($endpoint, $this->authHeaders());
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/servicio-animal/{$id}", $this->authHeaders());
    }

    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/servicio-animal', $data, $this->authHeaders() + ['Content-Type' => 'application/json']);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/servicio-animal/{$id}", $data, $this->authHeaders() + ['Content-Type' => 'application/json']);
    }

    public function eliminar(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->delete("/servicio-animal/{$id}", $this->authHeaders());
    }

    public function getAnimales(): array
    {
        if (!session('user.token')) return [];
        $r = $this->get('/animales?nopaginate=true', $this->authHeaders());
        $data = ($r['success'] ?? false) ? ($r['data'] ?? []) : [];
        return (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) ? $data['data'] : $data;
    }

    public function getSemenToros(): array
    {
        if (!session('user.token')) return [];
        $r = $this->get('/semen-toro?activo=1&nopaginate=true', $this->authHeaders());
        $data = ($r['success'] ?? false) ? ($r['data'] ?? []) : [];
        return (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) ? $data['data'] : $data;
    }

    public function getPersonalFinca(): array
    {
        if (!session('user.token')) return [];
        $r = $this->get('/personal-finca?nopaginate=true', $this->authHeaders());
        $data = ($r['success'] ?? false) ? ($r['data'] ?? []) : [];
        return (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) ? $data['data'] : $data;
    }

    public function getRegistrosCelo(): array
    {
        if (!session('user.token')) return [];
        $r = $this->get('/registro-celo?nopaginate=true', $this->authHeaders());
        $data = ($r['success'] ?? false) ? ($r['data'] ?? []) : [];
        return (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) ? $data['data'] : $data;
    }
}
