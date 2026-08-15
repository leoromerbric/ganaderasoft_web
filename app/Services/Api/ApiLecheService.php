<?php

namespace App\Services\Api;

use App\Services\Contracts\LecheServiceInterface;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Servicio encargado de gestionar la producción lechera
 * a través de la API v2 del backend.
 */
class ApiLecheService extends BaseApiService implements LecheServiceInterface
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
     * Obtiene la lista de registros de producción de leche con soporte nopaginate.
     *
     * @param int|null $lactanciaId
     * @param string|null $fechaInicio
     * @param string|null $fechaFin
     * @param bool $nopaginate
     * @return array
     */
    public function getRegistrosLeche(?int $lactanciaId = null, ?string $fechaInicio = null, ?string $fechaFin = null, bool $nopaginate = true): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'data' => []];
        }

        try {
            $params = array_filter([
                'lactancia_id' => $lactanciaId,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin'    => $fechaFin,
            ], fn ($v) => $v !== null);

            if ($nopaginate) {
                $params['nopaginate'] = 'true';
            }

            $endpoint = '/leche' . (!empty($params) ? '?' . http_build_query($params) : '');
            $response = $this->get($endpoint);

            if (!($response['success'] ?? false)) {
                return ['success' => false, 'data' => []];
            }

            return ['success' => true, 'data' => $this->extractDataCollection($response)];
        } catch (Exception $e) {
            Log::error('Error al consultar registros de leche: ' . $e->getMessage(), ['exception' => $e]);
            return ['success' => false, 'data' => []];
        }
    }

    /**
     * Obtiene un registro de producción de leche por ID.
     *
     * @param int $id
     * @return array
     */
    public function getRegistroLeche(int $id): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        try {
            return $this->get("/leche/{$id}");
        } catch (Exception $e) {
            Log::error("Error al obtener el registro de leche ID {$id}: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al obtener el detalle del pesaje de leche'];
        }
    }

    /**
     * Crea un nuevo registro de producción de leche.
     *
     * @param array $data
     * @return array
     */
    public function createRegistroLeche(array $data): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        try {
            return $this->post('/leche', $data);
        } catch (Exception $e) {
            Log::error('Error al registrar leche: ' . $e->getMessage(), ['payload' => $data]);
            return ['success' => false, 'message' => 'Error inesperado al registrar la producción de leche'];
        }
    }

    /**
     * Actualiza un registro de producción de leche existente.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateRegistroLeche(int $id, array $data): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        try {
            return $this->put("/leche/{$id}", $data);
        } catch (Exception $e) {
            Log::error("Error al actualizar el registro de leche ID {$id}: " . $e->getMessage(), ['payload' => $data]);
            return ['success' => false, 'message' => 'Error inesperado al actualizar el pesaje de leche'];
        }
    }

    /**
     * Elimina un registro de producción de leche.
     *
     * @param int $id
     * @return array
     */
    public function deleteRegistroLeche(int $id): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        try {
            return $this->delete("/leche/{$id}");
        } catch (Exception $e) {
            Log::error("Error al eliminar el registro de leche ID {$id}: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error inesperado al eliminar el pesaje de leche'];
        }
    }
}