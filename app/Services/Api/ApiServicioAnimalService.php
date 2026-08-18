<?php

namespace App\Services\Api;

use App\Services\Contracts\ServicioAnimalServiceInterface;

class ApiServicioAnimalService extends BaseApiService implements ServicioAnimalServiceInterface
{
    public function getList(?int $animalId = null, ?string $tipo = null, ?string $fechaInicio = null, ?string $fechaFin = null): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        $params = array_filter(['animal_id' => $animalId, 'tipo' => $tipo, 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]);
        $endpoint = '/servicio-animal?nopaginate=true' . (!empty($params) ? '&' . http_build_query($params) : '');
        return $this->get($endpoint);
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/servicio-animal/{$id}");
    }

    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/servicio-animal', $data);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/servicio-animal/{$id}", $data);
    }

    public function eliminar(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->delete("/servicio-animal/{$id}");
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
            \Illuminate\Support\Facades\Log::error('Error al obtener animales en ApiServicioAnimalService: ' . $e->getMessage());
            return [];
        }
    }

    public function getSemenToros(): array
    {
        try {
            if (!session('user.token')) return [];
            $response = $this->get('/semen-toro?nopaginate=true');
            if (!($response['success'] ?? false) || empty($response['data'])) return [];
            $data = $response['data'];
            return isset($data['data']) && is_array($data['data']) ? $data['data'] : (is_array($data) ? $data : []);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al obtener semen de toros en ApiServicioAnimalService: ' . $e->getMessage());
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
            \Illuminate\Support\Facades\Log::error('Error al obtener personal en ApiServicioAnimalService: ' . $e->getMessage());
            return [];
        }
    }

    public function getRegistrosCelo(): array
    {
        try {
            if (!session('user.token')) return [];
            $response = $this->get('/registro-celo?nopaginate=true');
            if (!($response['success'] ?? false) || empty($response['data'])) return [];
            $data = $response['data'];
            return isset($data['data']) && is_array($data['data']) ? $data['data'] : (is_array($data) ? $data : []);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al obtener registros de celo en ApiServicioAnimalService: ' . $e->getMessage());
            return [];
        }
    }
}
