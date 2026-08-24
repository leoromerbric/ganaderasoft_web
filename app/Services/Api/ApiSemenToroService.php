<?php

namespace App\Services\Api;

use App\Services\Contracts\SemenToroServiceInterface;

class ApiSemenToroService extends BaseApiService implements SemenToroServiceInterface
{
    /**
     * Obtiene el listado de registros de semen de toro.
     *
     * @param int|null $toroId
     * @param bool|null $activo
     * @return array
     */
    public function getList(?int $toroId = null, ?bool $activo = null, ?string $fechaInicio = null, ?string $fechaFin = null, ?int $fincaId = null, ?int $rebanoId = null): array
    {
        $params = [
            'toro_id'      => $toroId,
            'activo'       => $activo !== null ? ($activo ? '1' : '0') : null,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin'    => $fechaFin,
            'finca_id'     => $fincaId,
            'rebano_id'    => $rebanoId,
        ];

        return $this->get('/semen-toro' . $this->buildQuery($params, true));
    }

    /**
     * Obtiene el detalle de un registro de semen de toro por ID.
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        return $this->get("/semen-toro/{$id}");
    }

    /**
     * Registra una nueva muestra o lote de semen de toro.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->post('/semen-toro', $data);
    }

    /**
     * Actualiza un registro de semen de toro existente.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update(int $id, array $data): array
    {
        return $this->put("/semen-toro/{$id}", $data);
    }

    /**
     * Elimina un registro de semen de toro por su ID.
     *
     * @param int $id
     * @return array
     */
    public function eliminar(int $id): array
    {
        return $this->delete("/semen-toro/{$id}");
    }

    /**
     * Obtiene el catálogo de toros (machos) para selectores.
     *
     * @return array
     */
    public function getToros(): array
    {
        $response = $this->get('/animales' . $this->buildQuery(['sexo' => 'M'], true));
        return $this->extractCollection($response);
    }
}
