<?php

namespace App\Services\Api;

use App\Services\Contracts\PesoCorporalServiceInterface;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Servicio encargado de la gestión de registros de peso corporal
 * a través de la API v2.
 */
class ApiPesoCorporalService extends BaseApiService implements PesoCorporalServiceInterface
{
    /**
     * Extrae el array de datos de la respuesta de la API, soportando paginado y plano.
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
     * Obtiene la lista de registros de peso corporal.
     * Soporta nopaginate=true por defecto para retornar la lista completa.
     */
    public function getPesosCorporales(?int $animalId = null, ?string $fechaInicio = null, ?string $fechaFin = null, bool $nopaginate = true): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'data' => []];
        }

        $params = array_filter([
            'animal_id'    => $animalId,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin'    => $fechaFin,
        ]);

        if ($nopaginate) {
            $params['nopaginate'] = 'true';
        }

        $endpoint = '/peso-corporal' . (!empty($params) ? '?' . http_build_query($params) : '');
        $response = $this->get($endpoint);

        if (!($response['success'] ?? false)) {
            return ['success' => false, 'data' => []];
        }

        return ['success' => true, 'data' => $this->extractDataCollection($response)];
    }

    /**
     * Obtiene un registro de peso corporal por ID.
     */
    public function getPesoCorporal(int $id): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->get("/peso-corporal/{$id}");
    }

    /**
     * Crea un nuevo registro de peso corporal.
     */
    public function createPesoCorporal(array $data): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->post('/peso-corporal', $data);
    }

    /**
     * Actualiza un registro de peso corporal existente.
     */
    public function updatePesoCorporal(int $id, array $data): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->put("/peso-corporal/{$id}", $data);
    }

    /**
     * Elimina un registro de peso corporal.
     */
    public function deletePesoCorporal(int $id): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->delete("/peso-corporal/{$id}");
    }
}