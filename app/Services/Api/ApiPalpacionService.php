<?php

namespace App\Services\Api;

use App\Services\Contracts\PalpacionServiceInterface;

class ApiPalpacionService extends BaseApiService implements PalpacionServiceInterface
{
    /**
     * Obtiene la lista de palpaciones con filtros opcionales.
     *
     * @param int|null $animalId
     * @param string|null $tipo
     * @param string|null $fechaInicio
     * @param string|null $fechaFin
     * @param int|null $fincaId
     * @param int|null $rebanoId
     * @return array
     */
    public function getList(?int $animalId = null, ?string $tipo = null, ?string $fechaInicio = null, ?string $fechaFin = null, ?int $fincaId = null, ?int $rebanoId = null): array
    {
        $params = [
            'animal_id'    => $animalId,
            'tipo'         => $tipo,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin'    => $fechaFin,
            'finca_id'     => $fincaId,
            'rebano_id'    => $rebanoId,
        ];

        return $this->get('/palpacion' . $this->buildQuery($params, true));
    }

    /**
     * Obtiene el detalle de una palpación por su ID.
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        return $this->get("/palpacion/{$id}");
    }

    /**
     * Registra una nueva palpación.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->post('/palpacion', $data);
    }

    /**
     * Actualiza una palpación existente.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update(int $id, array $data): array
    {
        return $this->put("/palpacion/{$id}", $data);
    }

    /**
     * Elimina una palpación por su ID.
     *
     * @param int $id
     * @return array
     */
    public function eliminar(int $id): array
    {
        return $this->delete("/palpacion/{$id}");
    }

    /**
     * Obtiene el listado de animales para selectores.
     *
     * @param array $filters
     * @return array
     */
    public function getAnimales(array $filters = []): array
    {
        $response = $this->get('/animales' . $this->buildQuery($filters, true));
        return $this->extractCollection($response);
    }

    /**
     * Obtiene el listado de personal para selectores.
     *
     * @return array
     */
    public function getPersonalFinca(): array
    {
        $response = $this->get('/personal-finca' . $this->buildQuery([], true));
        return $this->extractCollection($response);
    }
}
