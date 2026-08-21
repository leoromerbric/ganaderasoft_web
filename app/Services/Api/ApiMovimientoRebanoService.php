<?php

namespace App\Services\Api;

use App\Services\Contracts\MovimientoRebanoServiceInterface;

/**
 * Servicio encargado de la gestión de movimientos de rebaño.
 */
class ApiMovimientoRebanoService extends BaseApiService implements MovimientoRebanoServiceInterface
{
    /**
     * Obtiene la lista de movimientos de rebaño.
     *
     * @param int|null $fincaId
     * @param int|null $rebanoId
     * @param bool $nopaginate
     * @return array
     */
    public function getList(?int $fincaId = null, ?int $rebanoId = null, bool $nopaginate = true): array
    {
        $params = [
            'finca_id'  => $fincaId,
            'rebano_id' => $rebanoId,
        ];

        $response = $this->get('/movimiento-rebano' . $this->buildQuery($params, $nopaginate));

        if (!($response['success'] ?? false)) {
            return ['success' => false, 'data' => [], 'message' => $response['message'] ?? 'Error al consultar movimientos'];
        }

        return ['success' => true, 'data' => $this->extractCollection($response)];
    }

    /**
     * Obtiene un movimiento de rebaño por su ID.
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        return $this->get("/movimiento-rebano/{$id}");
    }

    /**
     * Registra un nuevo movimiento de rebaño.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->post('/movimiento-rebano', $data);
    }

    /**
     * Actualiza un movimiento de rebaño existente.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update(int $id, array $data): array
    {
        return $this->put("/movimiento-rebano/{$id}", $data);
    }

    /**
     * Elimina un movimiento de rebaño por su ID.
     *
     * @param int $id
     * @return array
     */
    public function eliminar(int $id): array
    {
        return $this->delete("/movimiento-rebano/{$id}");
    }

    /**
     * Obtiene la lista completa de fincas para selectores.
     *
     * @return array
     */
    public function getFincas(): array
    {
        $response = $this->get('/fincas' . $this->buildQuery([], true));
        return $this->extractCollection($response);
    }

    /**
     * Obtiene la lista completa de rebaños para selectores.
     *
     * @return array
     */
    public function getRebanos(): array
    {
        $response = $this->get('/rebanos' . $this->buildQuery([], true));
        return $this->extractCollection($response);
    }

    /**
     * Obtiene la lista completa de animales para selectores.
     *
     * @return array
     */
    public function getAnimales(): array
    {
        $response = $this->get('/animales' . $this->buildQuery([], true));
        return $this->extractCollection($response);
    }
}
