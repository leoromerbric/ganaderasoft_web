<?php

namespace App\Services\Api;

use App\Services\Contracts\MovimientoRebanoServiceInterface;

class ApiMovimientoRebanoService extends BaseApiService implements MovimientoRebanoServiceInterface
{
    public function getList(?int $fincaId = null, ?int $rebanoId = null): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        $params = array_filter(['id_finca' => $fincaId, 'id_rebano' => $rebanoId]);
        $endpoint = '/movimiento-rebano' . (!empty($params) ? '?' . http_build_query($params) : '');
        return $this->get($endpoint);
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/movimiento-rebano/{$id}");
    }

    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/movimiento-rebano', $data);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/movimiento-rebano/{$id}", $data);
    }
}
