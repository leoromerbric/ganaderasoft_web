<?php

namespace App\Services\Api;

use App\Services\Contracts\HistoricoAplicacionServiceInterface;

class ApiHistoricoAplicacionService extends BaseApiService implements HistoricoAplicacionServiceInterface
{
    public function getList(?int $vacunaId = null, ?string $fechaInicio = null, ?string $fechaFin = null): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        $params = array_filter(['vacuna_id' => $vacunaId, 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]);
        $endpoint = '/historico-aplicacion' . (!empty($params) ? '?' . http_build_query($params) : '');
        return $this->get($endpoint);
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/historico-aplicacion/{$id}");
    }

    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/historico-aplicacion', $data);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/historico-aplicacion/{$id}", $data);
    }

    public function eliminar(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->delete("/historico-aplicacion/{$id}");
    }

    public function previewCampana(int $dosisId): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/historico-aplicacion/preview-campana', [
            'ha_dosis_id' => $dosisId,
        ]);
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
            \Illuminate\Support\Facades\Log::error('Error al obtener vacunas: ' . $e->getMessage());
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
            \Illuminate\Support\Facades\Log::error('Error al obtener casas comerciales: ' . $e->getMessage());
            return [];
        }
    }

    public function getDosis(): array
    {
        try {
            if (!session('user.token')) return [];
            $response = $this->get('/dosis?nopaginate=true');
            if (!($response['success'] ?? false) || empty($response['data'])) return [];
            $data = $response['data'];
            return isset($data['data']) && is_array($data['data']) ? $data['data'] : (is_array($data) ? $data : []);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al obtener dosis: ' . $e->getMessage());
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
            \Illuminate\Support\Facades\Log::error('Error al obtener animales: ' . $e->getMessage());
            return [];
        }
    }
}
