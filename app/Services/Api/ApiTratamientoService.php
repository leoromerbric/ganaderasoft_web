<?php

namespace App\Services\Api;

use App\Services\Contracts\TratamientoServiceInterface;

class ApiTratamientoService extends BaseApiService implements TratamientoServiceInterface
{
    public function getList(?int $diagnosticoId = null, ?string $fechaInicio = null, ?string $fechaFin = null): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        $params = array_filter(['diagnostico_id' => $diagnosticoId, 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]);
        $endpoint = '/tratamiento?nopaginate=true' . (!empty($params) ? '&' . http_build_query($params) : '');
        return $this->get($endpoint);
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/tratamiento/{$id}");
    }

    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/tratamiento', $data);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/tratamiento/{$id}", $data);
    }

    public function eliminar(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->delete("/tratamiento/{$id}");
    }

    public function getDiagnosticos(): array
    {
        try {
            if (!session('user.token')) return [];
            $response = $this->get('/diagnostico?nopaginate=true');
            if (!($response['success'] ?? false) || empty($response['data'])) return [];
            $data = $response['data'];
            return isset($data['data']) && is_array($data['data']) ? $data['data'] : (is_array($data) ? $data : []);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al obtener diagnosticos en ApiTratamientoService: ' . $e->getMessage());
            return [];
        }
    }
}
