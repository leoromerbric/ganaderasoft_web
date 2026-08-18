<?php

namespace App\Services\Api;

use App\Services\Contracts\DosisServiceInterface;

class ApiDosisService extends BaseApiService implements DosisServiceInterface
{
    public function getList(?int $vacunaId = null, ?bool $vigentes = null): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];

        $params = array_filter([
            'vacuna_id' => $vacunaId,
            'vigentes' => $vigentes ? 1 : null,
        ]);

        $endpoint = '/dosis' . (!empty($params) ? '?' . http_build_query($params) : '');
        return $this->get($endpoint);
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/dosis/{$id}");
    }

    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/dosis', $data);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/dosis/{$id}", $data);
    }

    public function eliminar(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->delete("/dosis/{$id}");
    }

    public function getVacunas(): array
    {
        try {
            if (!session('user.token')) return [];
            $response = $this->get('/vacunas?nopaginate=true');
            if (!($response['success'] ?? false) || empty($response['data'])) return [];
            $data = $response['data'];
            return isset($data['data']) && is_array($data['data']) ? $data['data'] : (is_array($data) ? $data : []);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al obtener vacunas en ApiDosisService: ' . $e->getMessage());
            return [];
        }
    }

    public function getCasasComerciales(): array
    {
        try {
            if (!session('user.token')) return [];
            $response = $this->get('/casa-comercial?nopaginate=true');
            if (!($response['success'] ?? false) || empty($response['data'])) return [];
            $data = $response['data'];
            return isset($data['data']) && is_array($data['data']) ? $data['data'] : (is_array($data) ? $data : []);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al obtener casas comerciales en ApiDosisService: ' . $e->getMessage());
            return [];
        }
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
            \Illuminate\Support\Facades\Log::error('Error al obtener animales en ApiDosisService: ' . $e->getMessage());
            return [];
        }
    }

    public function getRebanos(): array
    {
        try {
            if (!session('user.token')) return [];
            $response = $this->get('/rebanos?nopaginate=true');
            if (!($response['success'] ?? false) || empty($response['data'])) return [];
            $data = $response['data'];
            return isset($data['data']) && is_array($data['data']) ? $data['data'] : (is_array($data) ? $data : []);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al obtener rebaños en ApiDosisService: ' . $e->getMessage());
            return [];
        }
    }
}