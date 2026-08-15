<?php

namespace App\Services\Api;

use App\Services\Contracts\MovimientoRebanoServiceInterface;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Servicio encargado de la gestión de movimientos de rebaño
 * a través de la API v2.
 */
class ApiMovimientoRebanoService extends BaseApiService implements MovimientoRebanoServiceInterface
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
     * Obtiene la lista de movimientos de rebaño.
     * Soporta nopaginate=true para retornar la lista completa sin páginas.
     */
    public function getList(?int $fincaId = null, ?int $rebanoId = null, bool $nopaginate = true): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];

        $params = array_filter([
            'finca_id' => $fincaId,
            'rebano_id' => $rebanoId,
        ]);

        if ($nopaginate) {
            $params['nopaginate'] = 'true';
        }

        $endpoint = '/movimiento-rebano' . (!empty($params) ? '?' . http_build_query($params) : '');
        $response = $this->get($endpoint);

        if (!($response['success'] ?? false)) {
            return ['success' => false, 'data' => []];
        }

        return ['success' => true, 'data' => $this->extractDataCollection($response)];
    }

    /**
     * Obtiene un movimiento de rebaño por ID
     */
    public function getById(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'data' => []];
        return $this->get("/movimiento-rebano/{$id}");
    }

    /**
     * Crea un nuevo registro de movimiento de rebaño
     */
    public function create(array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->post('/movimiento-rebano', $data);
    }

    /**
     * Actualiza un registro de movimiento de rebaño existente
     */
    public function update(int $id, array $data): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->put("/movimiento-rebano/{$id}", $data);
    }

    /**
     * Elimina un movimiento de rebaño
     */
    public function eliminar(int $id): array
    {
        if (!session('user.token')) return ['success' => false, 'message' => 'Usuario no autenticado'];
        return $this->delete("/movimiento-rebano/{$id}");
    }

    /**
     * Obtiene la lista completa de fincas para los dropdowns (sin paginar)
     */
    public function getFincas(): array
    {
        try {
            if (!session('user.token')) return [];
            $response = $this->get('/fincas?nopaginate=true');
            return $this->extractDataCollection($response);
        } catch (Exception $e) {
            Log::error('Error al obtener fincas en ApiMovimientoRebanoService: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene la lista completa de rebaños para los dropdowns (sin paginar)
     */
    public function getRebanos(): array
    {
        try {
            if (!session('user.token')) return [];
            $response = $this->get('/rebanos?nopaginate=true');
            return $this->extractDataCollection($response);
        } catch (Exception $e) {
            Log::error('Error al obtener rebaños en ApiMovimientoRebanoService: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene la lista completa de animales para los dropdowns (sin paginar)
     */
    public function getAnimales(): array
    {
        try {
            if (!session('user.token')) return [];
            $response = $this->get('/animales?nopaginate=true');
            return $this->extractDataCollection($response);
        } catch (Exception $e) {
            Log::error('Error al obtener animales en ApiMovimientoRebanoService: ' . $e->getMessage());
            return [];
        }
    }
}
