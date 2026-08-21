<?php

namespace App\Services\Api;

use App\Services\Contracts\LecheServiceInterface;

/**
 * Servicio encargado de gestionar los registros de producción lechera.
 */
class ApiLecheService extends BaseApiService implements LecheServiceInterface
{
    /**
     * Obtiene la lista de registros de producción de leche con soporte nopaginate.
     *
     * @param int|null $lactanciaId
     * @param string|null $fechaInicio
     * @param string|null $fechaFin
     * @param bool $nopaginate
     * @return array
     */
    public function getRegistrosLeche(?int $lactanciaId = null, ?string $fechaInicio = null, ?string $fechaFin = null, bool $nopaginate = true): array
    {
        $params = [
            'lactancia_id' => $lactanciaId,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin'    => $fechaFin,
        ];

        $response = $this->get('/leche' . $this->buildQuery($params, $nopaginate));

        if (!($response['success'] ?? false)) {
            return ['success' => false, 'data' => [], 'message' => $response['message'] ?? 'Error al consultar registros de leche'];
        }

        return ['success' => true, 'data' => $this->extractCollection($response)];
    }

    /**
     * Obtiene un registro de producción de leche por ID.
     *
     * @param int $id
     * @return array
     */
    public function getRegistroLeche(int $id): array
    {
        return $this->get("/leche/{$id}");
    }

    /**
     * Crea un nuevo registro de producción de leche.
     *
     * @param array $data
     * @return array
     */
    public function createRegistroLeche(array $data): array
    {
        return $this->post('/leche', $data);
    }

    /**
     * Actualiza un registro de producción de leche existente.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateRegistroLeche(int $id, array $data): array
    {
        return $this->put("/leche/{$id}", $data);
    }

    /**
     * Elimina un registro de producción de leche por su ID.
     *
     * @param int $id
     * @return array
     */
    public function deleteRegistroLeche(int $id): array
    {
        return $this->delete("/leche/{$id}");
    }
}