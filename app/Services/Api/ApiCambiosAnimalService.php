<?php

namespace App\Services\Api;

use App\Services\Contracts\CambiosAnimalServiceInterface;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Servicio encargado de gestionar los cambios de etapa y desarrollo de animales
 * a través del consumo de la API v2 del backend.
 */
class ApiCambiosAnimalService extends BaseApiService implements CambiosAnimalServiceInterface
{
    /**
     * Verifica si el usuario actual posee un token de sesión activo.
     *
     * @return bool
     */
    protected function isAuthenticated(): bool
    {
        return session()->has('user.token');
    }

    /**
     * Extrae la colección de elementos de la respuesta de la API v2,
     * soportando tanto respuestas estructuradas paginadas (data.data) como planas (data).
     *
     * @param array $response Respuesta recibida de la API
     * @return array Lista de elementos extraídos
     */
    protected function extractDataCollection(array $response): array
    {
        if (!($response['success'] ?? false) || empty($response['data'])) {
            return [];
        }

        $data = $response['data'];

        return isset($data['data']) && is_array($data['data']) ? $data['data'] : (is_array($data) ? $data : []);
    }

    /**
     * Obtiene el listado de registros de cambios de etapa de animales con filtros opcionales.
     *
     * @param int|null $idAnimal ID del animal para filtrar
     * @param int|null $idFinca ID de la finca para filtrar
     * @return array Colección de registros de cambio
     */
    public function getList(?int $idAnimal = null, ?int $idFinca = null, bool $nopaginate = true): array
    {
        if (!$this->isAuthenticated()) {
            Log::warning('ApiCambiosAnimalService@getList - Intento de acceso sin autenticación');
            return [];
        }

        try {
            $queryParams = array_filter([
                'animal_id' => $idAnimal,
                'finca_id'  => $idFinca,
            ]);

            if ($nopaginate) {
                $queryParams['nopaginate'] = 'true';
            }

            $endpoint = '/cambios-animal' . (!empty($queryParams) ? '?' . http_build_query($queryParams) : '');
            $response = $this->get($endpoint);

            return $this->extractDataCollection($response);
        } catch (Exception $e) {
            Log::error('Error al obtener la lista de cambios de animales: ' . $e->getMessage(), [
                'exception' => $e,
                'animal_id' => $idAnimal,
                'finca_id'  => $idFinca
            ]);
            return [];
        }
    }

    /**
     * Registra un nuevo cambio de etapa de animal en la API v2.
     *
     * @param array $data Datos del cambio a registrar
     * @return array Respuesta estructurada de la API
     */
    public function create(array $data): array
    {
        if (!$this->isAuthenticated()) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        try {
            // Resolver animal_etapa_id si no fue provisto pero se dispone de animal_id
            if (empty($data['animal_etapa_id']) && !empty($data['animal_id'])) {
                $animal = $this->getAnimalById((int) $data['animal_id']);
                $animalEtapaId = data_get($animal, 'etapa_actual.id');
                if ($animalEtapaId) {
                    $data['animal_etapa_id'] = $animalEtapaId;
                }
            }

            return $this->post('/cambios-animal', $data);
        } catch (Exception $e) {
            Log::error('Error al crear el cambio de animal: ' . $e->getMessage(), [
                'exception' => $e,
                'payload'   => $data
            ]);
            return [
                'success' => false,
                'message' => 'Ocurrió un error inesperado al procesar la solicitud.'
            ];
        }
    }

    /**
     * Obtiene el detalle de un registro específico de cambio por su ID.
     *
     * @param int $id ID del registro de cambio
     * @return array Respuesta estructurada de la API
     */
    public function getById(int $id): array
    {
        if (!$this->isAuthenticated()) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        try {
            return $this->get("/cambios-animal/{$id}");
        } catch (Exception $e) {
            Log::error("Error al obtener el cambio de animal con ID {$id}: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al obtener el detalle del cambio'
            ];
        }
    }

    /**
     * Obtiene un catálogo general sin paginar desde la API v2.
     *
     * @param string $endpoint Endpoint de la API
     * @param string $nombreCatalogo Nombre descriptivo para logs de error
     * @return array Colección de elementos del catálogo
     */
    protected function getCatalogo(string $endpoint, string $nombreCatalogo): array
    {
        if (!$this->isAuthenticated()) {
            return [];
        }

        try {
            $response = $this->get("{$endpoint}?nopaginate=true");
            return $this->extractDataCollection($response);
        } catch (Exception $e) {
            Log::error("Error al obtener el catálogo de {$nombreCatalogo}: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return [];
        }
    }

    /**
     * Obtiene el listado de animales para la selección en los select/dropdowns.
     *
     * @return array Lista de animales
     */
    public function getAnimales(): array
    {
        return $this->getCatalogo('/animales', 'animales');
    }

    /**
     * Obtiene el catálogo de fincas para filtrado.
     *
     * @return array Lista de fincas
     */
    public function getFincas(): array
    {
        return $this->getCatalogo('/fincas', 'fincas');
    }

    /**
     * Obtiene el catálogo de rebaños para filtrado.
     *
     * @return array Lista de rebaños
     */
    public function getRebanos(): array
    {
        return $this->getCatalogo('/rebanos', 'rebaños');
    }

    /**
     * Calcula métricas y estadísticas agregadas a partir de la lista de cambios.
     *
     * @return array Métricas agregadas de los cambios
     */
    public function getEstadisticas(): array
    {
        try {
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
        } catch (Exception $e) {
            Log::error('Error al calcular las estadísticas de cambios de animal: ' . $e->getMessage());
            return [
                'total_cambios'   => 0,
                'por_etapa'       => [],
                'ultimos_30_dias' => 0,
                'promedio_peso'   => 0.0,
                'promedio_altura' => 0.0,
            ];
        }
    }

    /**
     * Obtiene la información detallada de un animal por su ID, incluyendo su etapa actual.
     *
     * @param int $id ID del animal
     * @return array Datos del animal o array vacío en caso de falla
     */
    public function getAnimalById(int $id): array
    {
        if (!$this->isAuthenticated()) {
            return [];
        }

        try {
            $response = $this->get("/animales/{$id}");

            if (($response['success'] ?? false) && isset($response['data'])) {
                return $response['data'];
            }

            return [];
        } catch (Exception $e) {
            Log::error("Error al obtener el animal con ID {$id}: " . $e->getMessage());
            return [];
        }
    }
}