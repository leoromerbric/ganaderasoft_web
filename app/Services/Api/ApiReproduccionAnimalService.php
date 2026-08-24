<?php

namespace App\Services\Api;

use App\Services\Contracts\ReproduccionAnimalServiceInterface;

class ApiReproduccionAnimalService extends BaseApiService implements ReproduccionAnimalServiceInterface
{
    /**
     * Obtiene el listado de eventos de reproducción animal.
     *
     * @param int|null $animalId
     * @param string|null $tipo
     * @param string|null $fechaInicio
     * @param string|null $fechaFin
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

        return $this->get('/reproduccion-animal' . $this->buildQuery($params, true));
    }

    /**
     * Obtiene el detalle de un evento reproductivo por su ID.
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        return $this->get("/reproduccion-animal/{$id}");
    }

    /**
     * Registra un nuevo evento reproductivo.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->post('/reproduccion-animal', $data);
    }

    /**
     * Actualiza un evento reproductivo existente.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update(int $id, array $data): array
    {
        return $this->put("/reproduccion-animal/{$id}", $data);
    }

    /**
     * Elimina un evento reproductivo por su ID.
     *
     * @param int $id
     * @return array
     */
    public function eliminar(int $id): array
    {
        return $this->delete("/reproduccion-animal/{$id}");
    }

    /**
     * Obtiene el listado de animales para selectores.
     *
     * @return array
     */
    public function getAnimales(): array
    {
        $response = $this->get('/animales' . $this->buildQuery([], true));
        return $this->extractCollection($response);
    }
}
