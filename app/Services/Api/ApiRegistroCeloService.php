<?php

namespace App\Services\Api;

use App\Services\Contracts\RegistroCeloServiceInterface;

class ApiRegistroCeloService extends BaseApiService implements RegistroCeloServiceInterface
{
    public function getList(?int $animalId = null, ?string $fechaInicio = null, ?string $fechaFin = null): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];

        $params = array_filter([
            'animal_id'   => $animalId,
            'fecha_inicio'=> $fechaInicio,
            'fecha_fin'   => $fechaFin,
        ]);
        $endpoint = '/registro-celo?nopaginate=true' . (!empty($params) ? '&' . http_build_query($params) : '');
        return $this->get($endpoint);
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/registro-celo/{$id}");
    }

    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/registro-celo', $data);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/registro-celo/{$id}", $data)) ? $data['data'] : $data;
    }
}
