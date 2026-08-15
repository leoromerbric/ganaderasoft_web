<?php

namespace App\Services\Api;

use App\Services\Contracts\LactanciaServiceInterface;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Servicio encargado de gestionar los períodos de lactancia
 * a través de la API v2 del backend.
 */
class ApiLactanciaService extends BaseApiService implements LactanciaServiceInterface
{
    /**
     * Extrae la colección de datos de la respuesta de la API v2,
     * soportando tanto respuestas paginadas (data.data) como listas planas (data).
     *
     * @param array $response Respuesta de la API
     * @return array Elementos extraídos
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
     * Obtiene el listado de períodos de lactancia con filtros opcionales.
     * Soporta nopaginate=true por defecto.
     *
     * @param int|null $animalId ID del animal para filtrar
     * @param bool|null $activa Estado del período de lactancia (activa o finalizada)
     * @param string|null $fechaInicio Rango inicial de fecha de inicio
     * @param string|null $fechaFin Rango final de fecha de inicio
     * @param bool $nopaginate Desactivar paginación en backend
     * @return array Lista de períodos de lactancia
     */
    public function getLactancias(?int $animalId = null, ?bool $activa = null, ?string $fechaInicio = null, ?string $fechaFin = null, bool $nopaginate = true): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'data' => []];
        }

        try {
            $params = array_filter([
                'animal_id'    => $animalId,
                'activa'       => $activa !== null ? ($activa ? 1 : 0) : null,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin'    => $fechaFin,
            ], fn ($v) => $v !== null);

            if ($nopaginate) {
                $params['nopaginate'] = 'true';
            }

            $endpoint = '/lactancia' . (!empty($params) ? '?' . http_build_query($params) : '');
            $response = $this->get($endpoint);

            if (!($response['success'] ?? false)) {
                return ['success' => false, 'data' => []];
            }

            return ['success' => true, 'data' => $this->extractDataCollection($response)];
        } catch (Exception $e) {
            Log::error('Error al consultar lactancias: ' . $e->getMessage(), ['exception' => $e]);
            return ['success' => false, 'data' => []];
        }
    }

    /**
     * Obtiene los detalles de un período de lactancia específico por su ID.
     *
     * @param int $id ID de la lactancia
     * @return array Datos del período de lactancia
     */
    public function getLactancia(int $id): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        try {
            return $this->get("/lactancia/{$id}");
        } catch (Exception $e) {
            Log::error("Error al obtener la lactancia ID {$id}: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al obtener el detalle de la lactancia'];
        }
    }

    /**
     * Registra un nuevo período de lactancia.
     *
     * @param array $data Datos de la lactancia
     * @return array Respuesta estructurada de la API
     */
    public function createLactancia(array $data): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        try {
            return $this->post('/lactancia', $data);
        } catch (Exception $e) {
            Log::error('Error al registrar lactancia: ' . $e->getMessage(), ['payload' => $data]);
            return ['success' => false, 'message' => 'Error inesperado al registrar el período de lactancia'];
        }
    }

    /**
     * Actualiza un período de lactancia existente.
     *
     * @param int $id ID de la lactancia
     * @param array $data Datos a actualizar
     * @return array Respuesta estructurada de la API
     */
    public function updateLactancia(int $id, array $data): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        try {
            return $this->put("/lactancia/{$id}", $data);
        } catch (Exception $e) {
            Log::error("Error al actualizar la lactancia ID {$id}: " . $e->getMessage(), ['payload' => $data]);
            return ['success' => false, 'message' => 'Error inesperado al actualizar la lactancia'];
        }
    }

    /**
     * Elimina un período de lactancia por su ID.
     *
     * @param int $id ID de la lactancia
     * @return array Respuesta de la API
     */
    public function deleteLactancia(int $id): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        try {
            return $this->delete("/lactancia/{$id}");
        } catch (Exception $e) {
            Log::error("Error al eliminar la lactancia ID {$id}: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error inesperado al eliminar la lactancia'];
        }
    }
}