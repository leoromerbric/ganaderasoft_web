<?php

namespace App\Services\Api;

use App\Services\Contracts\CasaComercialServiceInterface;

class ApiCasaComercialService extends BaseApiService implements CasaComercialServiceInterface
{
    public function getList(?string $laboratorio = null): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        $endpoint = '/casas-comerciales?nopaginate=true' . ($laboratorio ? '&laboratorio=' . urlencode($laboratorio) : '');
        return $this->get($endpoint);
    }

    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/casas-comerciales/{$id}");
    }

    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/casas-comerciales', $data);
    }

    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/casas-comerciales/{$id}", $data);
    }

    public function eliminar(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->delete("/casas-comerciales/{$id}");
    }
}
