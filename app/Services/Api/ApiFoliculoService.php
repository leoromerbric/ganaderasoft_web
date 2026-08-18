<?php

namespace App\Services\Api;

use App\Services\Contracts\FoliculoServiceInterface;

class ApiFoliculoService extends BaseApiService implements FoliculoServiceInterface
{
    protected string $endpoint = '/foliculos';

    // Obtener lista de clasificaciones de folículos (por defecto solicita nopaginate=true)
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
