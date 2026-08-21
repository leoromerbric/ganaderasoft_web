<?php

namespace App\Services\Api;

use App\Services\Contracts\MedidasCorporalesServiceInterface;

/**
 * Servicio encargado de gestionar las medidas corporales (morfometría).
 */
class ApiMedidasCorporalesService extends BaseApiService implements MedidasCorporalesServiceInterface
{
    /**
     * Obtiene la lista de medidas corporales con soporte nopaginate.
     *
     * @param int|null $animalId
     * @param int|null $etapaId
     * @param bool $nopaginate
     * @return array
     */
    public function getMedidasCorporales(?int $animalId = null, ?int $etapaId = null, bool $nopaginate = true): array
    {
        $params = [
            'animal_id' => $animalId,
            'etapa_id'  => $etapaId,
        ];

        $response = $this->get('/medidas-corporales' . $this->buildQuery($params, $nopaginate));

        if (!($response['success'] ?? false)) {
            return ['success' => false, 'data' => [], 'message' => $response['message'] ?? 'Error al consultar medidas corporales'];
        }

        return ['success' => true, 'data' => $this->extractCollection($response)];
    }

    /**
     * Obtiene un registro de medida corporal por ID.
     *
     * @param int $id
     * @return array
     */
    public function getMedidaCorporal(int $id): array
    {
        return $this->get("/medidas-corporales/{$id}");
    }

    /**
     * Crea un nuevo registro de medidas corporales.
     *
     * @param array $data
     * @return array
     */
    public function createMedidaCorporal(array $data): array
    {
        return $this->post('/medidas-corporales', $data);
    }

    /**
     * Actualiza un registro de medida corporal existente.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateMedidaCorporal(int $id, array $data): array
    {
        return $this->put("/medidas-corporales/{$id}", $data);
    }

    /**
     * Elimina un registro de medida corporal.
     *
     * @param int $id
     * @return array
     */
    public function deleteMedidaCorporal(int $id): array
    {
        return $this->delete("/medidas-corporales/{$id}");
    }

    /**
     * Obtiene los índices zoométricos calculados para una medición.
     *
     * @param int $id
     * @return array
     */
    public function getIndicesByMedida(int $id): array
    {
        return $this->get("/medidas-corporales/{$id}/indices");
    }

    /**
     * Obtiene la evolución histórica de índices zoométricos de un animal.
     *
     * @param int $animalId
     * @return array
     */
    public function getEvolucionIndices(int $animalId): array
    {
        return $this->get("/animales/{$animalId}/indices-corporales");
    }
}