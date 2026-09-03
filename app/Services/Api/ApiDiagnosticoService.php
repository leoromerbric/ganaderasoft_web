<?php

namespace App\Services\Api;

use App\Services\Contracts\DiagnosticoServiceInterface;

class ApiDiagnosticoService extends BaseApiService implements DiagnosticoServiceInterface
{
    /**
     * Obtiene la lista de diagnósticos según los filtros dados.
     *
     * @param int|null $animalId
     * @param string|null $tipo
     * @param string|null $fechaInicio
     * @param string|null $fechaFin
     * @return array
     */
    public function getList(?int $animalId = null, ?string $tipo = null, ?string $fechaInicio = null, ?string $fechaFin = null): array
    {
        $params = [
            'animal_id'    => $animalId,
            'tipo'         => $tipo,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin'    => $fechaFin,
        ];

        return $this->get('/diagnostico' . $this->buildQuery($params, true));
    }

    /**
     * Obtiene el detalle de un diagnóstico por su ID.
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        return $this->get("/diagnostico/{$id}");
    }

    /**
     * Registra un nuevo diagnóstico.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->post('/diagnostico', $data);
    }

    /**
     * Actualiza un diagnóstico existente.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update(int $id, array $data): array
    {
        return $this->put("/diagnostico/{$id}", $data);
    }

    /**
     * Elimina un diagnóstico por su ID.
     *
     * @param int $id
     * @return array
     */
    public function eliminar(int $id): array
    {
        return $this->delete("/diagnostico/{$id}");
    }

    /**
     * Obtiene el listado de animales para los selectores.
     *
     * @param array $filters
     * @return array
     */
    public function getAnimales(array $filters = []): array
    {
        $response = $this->get('/animales' . $this->buildQuery($filters, true));
        return $this->extractCollection($response);
    }
}
