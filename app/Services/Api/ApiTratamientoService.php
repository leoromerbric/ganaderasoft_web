<?php

namespace App\Services\Api;

use App\Services\Contracts\TratamientoServiceInterface;

class ApiTratamientoService extends BaseApiService implements TratamientoServiceInterface
{
    /**
     * Obtiene el listado de tratamientos con filtros opcionales.
     *
     * @param int|null $diagnosticoId
     * @param string|null $fechaInicio
     * @param string|null $fechaFin
     * @return array
     */
    public function getList(?int $diagnosticoId = null, ?string $fechaInicio = null, ?string $fechaFin = null): array
    {
        $params = [
            'diagnostico_id' => $diagnosticoId,
            'fecha_inicio'   => $fechaInicio,
            'fecha_fin'      => $fechaFin,
        ];

        return $this->get('/tratamiento' . $this->buildQuery($params, true));
    }

    /**
     * Obtiene el detalle de un tratamiento por su ID.
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        return $this->get("/tratamiento/{$id}");
    }

    /**
     * Registra un nuevo tratamiento.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->post('/tratamiento', $data);
    }

    /**
     * Actualiza un tratamiento existente.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update(int $id, array $data): array
    {
        return $this->put("/tratamiento/{$id}", $data);
    }

    /**
     * Elimina un tratamiento por su ID.
     *
     * @param int $id
     * @return array
     */
    public function eliminar(int $id): array
    {
        return $this->delete("/tratamiento/{$id}");
    }

    /**
     * Obtiene el listado de diagnósticos para selectores.
     *
     * @return array
     */
    public function getDiagnosticos(): array
    {
        $response = $this->get('/diagnostico' . $this->buildQuery([], true));
        return $this->extractCollection($response);
    }
}
