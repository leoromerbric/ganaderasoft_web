<?php

namespace App\Services\Api;

use App\Services\Contracts\SemenToroServiceInterface;

class ApiSemenToroService extends BaseApiService implements SemenToroServiceInterface
{
    public function getList(?int $toroId = null, ?bool $activo = null): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        $params = array_filter([
            'toro_id' => $toroId, 
            'activo' => $activo !== null ? ($activo ? '1' : '0') : null,
            'nopaginate' => 'true'
        ]);
        $endpoint = '/semen-toro' . (!empty($params) ? '?' . http_build_query($params) : '');
        return $this->get($endpoint);
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/semen-toro/{$id}");
    }

    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/semen-toro', $data);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/semen-toro/{$id}", $data);
    }

    public function eliminar(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->delete("/semen-toro/{$id}");
    }

    public function getToros(): array
    {
        try {
            if (!session('user.token')) return [];
            $response = $this->get('/animales?nopaginate=true&sexo=M');
            if (!($response['success'] ?? false) || empty($response['data'])) return [];
            $data = $response['data'];
            return isset($data['data']) && is_array($data['data']) ? $data['data'] : (is_array($data) ? $data : []);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al obtener toros en ApiSemenToroService: ' . $e->getMessage());
            return [];
        }
    }
}
