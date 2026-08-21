<?php

namespace App\Services\Api;

use App\Services\Contracts\PesoCorporalServiceInterface;

/**
 * Servicio encargado de la gestión de registros de peso corporal.
 */
class ApiPesoCorporalService extends BaseApiService implements PesoCorporalServiceInterface
{
    /**
     * Obtiene la lista de registros de peso corporal con filtros y soporte nopaginate.
     *
     * @param int|null $animalId
     * @param string|null $fechaInicio
     * @param string|null $fechaFin
     * @param bool $nopaginate
     * @return array
     */
    public function getPesosCorporales(?int $animalId = null, ?string $fechaInicio = null, ?string $fechaFin = null, bool $nopaginate = true): array
    {
        $params = [
            'animal_id'    => $animalId,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin'    => $fechaFin,
        ];

        $response = $this->get('/peso-corporal' . $this->buildQuery($params, $nopaginate));

        if (!($response['success'] ?? false)) {
            return ['success' => false, 'data' => [], 'message' => $response['message'] ?? 'Error al consultar pesos corporales'];
        }

        return ['success' => true, 'data' => $this->extractCollection($response)];
    }

    /**
     * Obtiene un registro de peso corporal por su ID.
     *
     * @param int $id
     * @return array
     */
    public function getPesoCorporal(int $id): array
    {
        return $this->get("/peso-corporal/{$id}");
    }

    /**
     * Crea un nuevo registro de peso corporal.
     *
     * @param array $data
     * @return array
     */
    public function createPesoCorporal(array $data): array
    {
        return $this->post('/peso-corporal', $data);
    }

    /**
     * Actualiza un registro de peso corporal existente.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updatePesoCorporal(int $id, array $data): array
    {
        return $this->put("/peso-corporal/{$id}", $data);
    }

    /**
     * Elimina un registro de peso corporal por su ID.
     *
     * @param int $id
     * @return array
     */
    public function deletePesoCorporal(int $id): array
    {
        return $this->delete("/peso-corporal/{$id}");
    }
}