<?php

namespace App\Services\Api;

use App\Services\Contracts\LactanciaServiceInterface;

/**
 * Servicio encargado de gestionar los períodos de lactancia.
 */
class ApiLactanciaService extends BaseApiService implements LactanciaServiceInterface
{
    /**
     * Obtiene el listado de períodos de lactancia con filtros opcionales.
     *
     * @param int|null $animalId
     * @param bool|null $activa
     * @param string|null $fechaInicio
     * @param string|null $fechaFin
     * @param bool $nopaginate
     * @return array
     */
    public function getLactancias(?int $animalId = null, ?bool $activa = null, ?string $fechaInicio = null, ?string $fechaFin = null, bool $nopaginate = true): array
    {
        $params = [
            'animal_id'    => $animalId,
            'activa'       => $activa !== null ? ($activa ? 1 : 0) : null,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin'    => $fechaFin,
        ];

        $response = $this->get('/lactancia' . $this->buildQuery($params, $nopaginate));

        if (!($response['success'] ?? false)) {
            return ['success' => false, 'data' => [], 'message' => $response['message'] ?? 'Error al consultar lactancias'];
        }

        return ['success' => true, 'data' => $this->extractCollection($response)];
    }

    /**
     * Obtiene los detalles de un período de lactancia específico por su ID.
     *
     * @param int $id
     * @return array
     */
    public function getLactancia(int $id): array
    {
        return $this->get("/lactancia/{$id}");
    }

    /**
     * Registra un nuevo período de lactancia.
     *
     * @param array $data
     * @return array
     */
    public function createLactancia(array $data): array
    {
        return $this->post('/lactancia', $data);
    }

    /**
     * Actualiza un período de lactancia existente.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateLactancia(int $id, array $data): array
    {
        return $this->put("/lactancia/{$id}", $data);
    }

    /**
     * Elimina un período de lactancia por su ID.
     *
     * @param int $id
     * @return array
     */
    public function deleteLactancia(int $id): array
    {
        return $this->delete("/lactancia/{$id}");
    }
}