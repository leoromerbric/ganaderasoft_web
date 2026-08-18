<?php

namespace App\Services\Api;

use App\Services\Contracts\ReproduccionAnimalServiceInterface;

class ApiReproduccionAnimalService extends BaseApiService implements ReproduccionAnimalServiceInterface
{
    public function getList(?int $animalId = null, ?string $tipo = null, ?string $fechaInicio = null, ?string $fechaFin = null): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        $params = array_filter(['animal_id' => $animalId, 'tipo' => $tipo, 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]);
        $endpoint = '/reproduccion-animal?nopaginate=true' . (!empty($params) ? '&' . http_build_query($params) : '');
        return $this->get($endpoint);
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/reproduccion-animal/{$id}");
    }

    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/reproduccion-animal', $data);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/reproduccion-animal/{$id}", $data);
    }

    public function eliminar(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->delete("/reproduccion-animal/{$id}");
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
            \Illuminate\Support\Facades\Log::error('Error al obtener animales en ApiReproduccionAnimalService: ' . $e->getMessage());
            return [];
        }
    }
}
