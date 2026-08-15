<?php

namespace App\Services\Api;

use App\Services\Contracts\MedidasCorporalesServiceInterface;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Servicio encargado de gestionar las medidas corporales (morfometría)
 * a través de la API v2 del backend.
 */
class ApiMedidasCorporalesService extends BaseApiService implements MedidasCorporalesServiceInterface
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
     * Obtiene la lista de medidas corporales con soporte nopaginate.
     *
     * @param int|null $animalId
     * @param int|null $etapaId
     * @param bool $nopaginate
     * @return array
     */
    public function getMedidasCorporales(?int $animalId = null, ?int $etapaId = null, bool $nopaginate = true): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'data' => []];
        }

        try {
            $params = array_filter([
                'animal_id' => $animalId,
                'etapa_id'  => $etapaId,
            ], fn ($v) => $v !== null);

            if ($nopaginate) {
                $params['nopaginate'] = 'true';
            }

            $endpoint = '/medidas-corporales' . (!empty($params) ? '?' . http_build_query($params) : '');
            $response = $this->get($endpoint);

            if (!($response['success'] ?? false)) {
                return ['success' => false, 'data' => []];
            }

            return ['success' => true, 'data' => $this->extractDataCollection($response)];
        } catch (Exception $e) {
            Log::error('Error al consultar medidas corporales: ' . $e->getMessage(), ['exception' => $e]);
            return ['success' => false, 'data' => []];
        }
    }

    /**
     * Obtiene un registro de medida corporal por ID.
     *
     * @param int $id
     * @return array
     */
    public function getMedidaCorporal(int $id): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        try {
            return $this->get("/medidas-corporales/{$id}");
        } catch (Exception $e) {
            Log::error("Error al obtener la medida corporal ID {$id}: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al obtener el detalle de medidas corporales'];
        }
    }

    /**
     * Crea un nuevo registro de medidas corporales.
     *
     * @param array $data
     * @return array
     */
    public function createMedidaCorporal(array $data): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        try {
            return $this->post('/medidas-corporales', $data);
        } catch (Exception $e) {
            Log::error('Error al registrar medidas corporales: ' . $e->getMessage(), ['payload' => $data]);
            return ['success' => false, 'message' => 'Error inesperado al registrar las medidas corporales'];
        }
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
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        try {
            return $this->put("/medidas-corporales/{$id}", $data);
        } catch (Exception $e) {
            Log::error("Error al actualizar la medida corporal ID {$id}: " . $e->getMessage(), ['payload' => $data]);
            return ['success' => false, 'message' => 'Error inesperado al actualizar las medidas corporales'];
        }
    }

    /**
     * Elimina un registro de medida corporal.
     *
     * @param int $id
     * @return array
     */
    public function deleteMedidaCorporal(int $id): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        try {
            return $this->delete("/medidas-corporales/{$id}");
        } catch (Exception $e) {
            Log::error("Error al eliminar la medida corporal ID {$id}: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error inesperado al eliminar el registro de medidas corporales'];
        }
    }
}