<?php

namespace App\Services\Api;

use App\Services\Contracts\PalpacionServiceInterface;

class ApiPalpacionService extends BaseApiService implements PalpacionServiceInterface
{
    public function getList(?int $animalId = null, ?string $tipo = null, ?string $fechaInicio = null, ?string $fechaFin = null): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        $params = array_filter(['animal_id' => $animalId, 'tipo' => $tipo, 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]);
        $endpoint = '/palpacion?nopaginate=true' . (!empty($params) ? '&' . http_build_query($params) : '');
        return $this->get($endpoint);
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/palpacion/{$id}");
    }

    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/palpacion', $data);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/palpacion/{$id}", $data);
    }

    public function eliminar(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->delete("/palpacion/{$id}");
    }

    public function getAnimales(): array
    {
        try {
            if (!session('user.token')) return [];
            $response = $this->get('/animales?nopaginate=true');
            if (!($response['success'] ?? false) || empty($response['data'])) return [];
            $data = $response['data'];
            return isset($data['data']) && is_array($data['data']) ? $data['data'] : (is_array($data) ? $data : []);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al obtener animales en ApiPalpacionService: ' . $e->getMessage());
            return [];
        }
    }

    public function getPersonalFinca(): array
    {
        try {
            if (!session('user.token')) return [];
            $response = $this->get('/personal?nopaginate=true');
            if (!($response['success'] ?? false) || empty($response['data'])) return [];
            $data = $response['data'];
            return isset($data['data']) && is_array($data['data']) ? $data['data'] : (is_array($data) ? $data : []);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al obtener personal en ApiPalpacionService: ' . $e->getMessage());
            return [];
        }
    }
}
