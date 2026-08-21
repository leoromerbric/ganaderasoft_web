<?php

namespace App\Services\Api;

use App\Services\Contracts\ServicioAnimalServiceInterface;

class ApiServicioAnimalService extends BaseApiService implements ServicioAnimalServiceInterface
{
    /**
     * Obtiene la lista de servicios reproductivos / montas / inseminaciones.
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

        return $this->get('/servicio-animal' . $this->buildQuery($params, true));
    }

    /**
     * Obtiene el detalle de un servicio reproductivo por ID.
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        return $this->get("/servicio-animal/{$id}");
    }

    /**
     * Registra un nuevo servicio reproductivo.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->post('/servicio-animal', $data);
    }

    /**
     * Actualiza un servicio reproductivo existente.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update(int $id, array $data): array
    {
        return $this->put("/servicio-animal/{$id}", $data);
    }

    /**
     * Elimina un servicio reproductivo por su ID.
     *
     * @param int $id
     * @return array
     */
    public function eliminar(int $id): array
    {
        return $this->delete("/servicio-animal/{$id}");
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

    /**
     * Obtiene el catálogo de semen de toros para selectores.
     *
     * @return array
     */
    public function getSemenToros(): array
    {
        $response = $this->get('/semen-toro' . $this->buildQuery([], true));
        return $this->extractCollection($response);
    }

    /**
     * Obtiene el listado de personal para selectores.
     *
     * @return array
     */
    public function getPersonalFinca(): array
    {
        $response = $this->get('/personal' . $this->buildQuery([], true));
        return $this->extractCollection($response);
    }

    /**
     * Obtiene el listado de registros de celo para selectores.
     *
     * @return array
     */
    public function getRegistrosCelo(): array
    {
        $response = $this->get('/registro-celo' . $this->buildQuery([], true));
        return $this->extractCollection($response);
    }
}
