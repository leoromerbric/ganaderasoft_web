<?php

namespace App\Services\Api;

use App\Services\Contracts\CambiosAnimalServiceInterface;

/**
 * Servicio encargado de gestionar los cambios de etapa y desarrollo de animales.
 */
class ApiCambiosAnimalService extends BaseApiService implements CambiosAnimalServiceInterface
{
    /**
     * Obtiene el listado de registros de cambios de etapa de animales con filtros opcionales.
     *
     * @param int|null $idAnimal ID del animal para filtrar.
     * @param int|null $idFinca ID de la finca para filtrar.
     * @param bool $nopaginate
     * @return array
     */
    public function getList(?int $idAnimal = null, ?int $idFinca = null, bool $nopaginate = true): array
    {
        $queryParams = [
            'animal_id' => $idAnimal,
            'finca_id'  => $idFinca,
        ];

        $response = $this->get('/cambios-animal' . $this->buildQuery($queryParams, $nopaginate));
        return $this->extractCollection($response);
    }

    /**
     * Registra un nuevo cambio de etapa de animal.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        // Resolver animal_etapa_id si no fue provisto pero se dispone de animal_id
        if (empty($data['animal_etapa_id']) && !empty($data['animal_id'])) {
            $animal = $this->getAnimalById((int) $data['animal_id']);
            $animalEtapaId = data_get($animal, 'etapa_actual.id');
            if ($animalEtapaId) {
                $data['animal_etapa_id'] = $animalEtapaId;
            }
        }

        return $this->post('/cambios-animal', $data);
    }

    /**
     * Obtiene el detalle de un registro específico de cambio por su ID.
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        return $this->get("/cambios-animal/{$id}");
    }

    /**
     * Obtiene el listado de animales para los selectores.
     *
     * @return array
     */
    public function getAnimales(): array
    {
        $response = $this->get('/animales' . $this->buildQuery([], true));
        return $this->extractCollection($response);
    }

    /**
     * Obtiene el catálogo de fincas para filtrado.
     *
     * @return array
     */
    public function getFincas(): array
    {
        $response = $this->get('/fincas' . $this->buildQuery([], true));
        return $this->extractCollection($response);
    }

    /**
     * Obtiene el catálogo de rebaños para filtrado.
     *
     * @return array
     */
    public function getRebanos(): array
    {
        $response = $this->get('/rebanos' . $this->buildQuery([], true));
        return $this->extractCollection($response);
    }

    /**
     * Calcula métricas y estadísticas agregadas a partir de la lista de cambios.
     *
     * @return array
     */
    public function getEstadisticas(): array
    {
        $cambios = $this->getList();

        $estadisticas = [
            'total_cambios'   => count($cambios),
            'por_etapa'       => [],
            'ultimos_30_dias' => 0,
            'promedio_peso'   => 0.0,
            'promedio_altura' => 0.0,
        ];

        if (empty($cambios)) {
            return $estadisticas;
        }

        $porEtapa = [];
        $pesos = [];
        $alturas = [];
        $fechaLimite = date('Y-m-d', strtotime('-30 days'));
        $recientes = 0;

        foreach ($cambios as $cambio) {
            if (!is_array($cambio)) {
                continue;
            }

            $etapa = $cambio['etapa_cambio'] ?? null;
            if ($etapa) {
                $porEtapa[$etapa] = ($porEtapa[$etapa] ?? 0) + 1;
            }

            if (isset($cambio['peso']) && is_numeric($cambio['peso']) && (float) $cambio['peso'] > 0) {
                $pesos[] = (float) $cambio['peso'];
            }

            if (isset($cambio['altura']) && is_numeric($cambio['altura']) && (float) $cambio['altura'] > 0) {
                $alturas[] = (float) $cambio['altura'];
            }

            $fecha = $cambio['fecha_cambio'] ?? null;
            if ($fecha && $fecha >= $fechaLimite) {
                $recientes++;
            }
        }

        $estadisticas['por_etapa']       = $porEtapa;
        $estadisticas['ultimos_30_dias'] = $recientes;
        $estadisticas['promedio_peso']   = !empty($pesos) ? round(array_sum($pesos) / count($pesos), 1) : 0.0;
        $estadisticas['promedio_altura'] = !empty($alturas) ? round(array_sum($alturas) / count($alturas), 1) : 0.0;

        return $estadisticas;
    }

    /**
     * Obtiene la información detallada de un animal por su ID.
     *
     * @param int $id
     * @return array
     */
    public function getAnimalById(int $id): array
    {
        $response = $this->get("/animales/{$id}");
        return $this->extractItem($response) ?? [];
    }
}