<?php

namespace App\Services\Api;

use App\Services\Contracts\EtapaServiceInterface;

class ApiEtapaService extends BaseApiService implements EtapaServiceInterface
{
    protected string $endpoint = '/etapas';

    // Obtener lista de etapas de crecimiento (por defecto solicita nopaginate=true)
    public function getAll(array $params = []): array
    {
        $query = !empty($params) ? '?' . http_build_query($params) : '?nopaginate=true';
        $res = $this->get($this->endpoint . $query);

        return $res['data'] ?? [];
    }

    // Obtener detalle de un registro por ID
    public function getById(int $id): array
    {
        return $this->get($this->endpoint . '/' . $id);
    }

    // Crear un nuevo registro
    public function create(array $data): array
    {
        return $this->post($this->endpoint, $data);
    }

    // Actualizar un registro existente
    public function update(int $id, array $data): array
    {
        return $this->put($this->endpoint . '/' . $id, $data);
    }

    // Eliminar un registro por ID
    public function deleteItem(int $id): array
    {
        return $this->delete($this->endpoint . '/' . $id);
    }
}
