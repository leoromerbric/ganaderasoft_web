<?php

namespace App\Services\Api;

use App\Services\Contracts\ConfiguracionServiceInterface;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Servicio encargado de gestionar las opciones de configuración y catálogos globales
 * a través del consumo de la API v2 del backend.
 */
class ApiConfiguracionService extends BaseApiService implements ConfiguracionServiceInterface
{
    /**
     * Verifica si el usuario posee un token de sesión activo.
     *
     * @return bool
     */
    protected function isAuthenticated(): bool
    {
        return session()->has('user.token');
    }

    /**
     * Helper genérico para obtener catálogos de configuración sin paginar.
     *
     * @param string $endpoint Endpoint relativo de la API
     * @param string $nombreOp Nombre descriptivo para logging de errores
     * @return array Estructura o listado devuelto por la API
     */
    protected function fetchOption(string $endpoint, string $nombreOp): array
    {
        if (!$this->isAuthenticated()) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        try {
            return $this->get($endpoint);
        } catch (Exception $e) {
            Log::error("Error al obtener la configuración de {$nombreOp}: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return [
                'success' => false,
                'message' => "Error al obtener catálogo de {$nombreOp}"
            ];
        }
    }

    /**
     * Obtiene el listado completo de etapas del sistema sin paginación.
     *
     * @return array Colección de etapas de desarrollo
     */
    public function getEtapas(): array
    {
        if (!$this->isAuthenticated()) {
            return [];
        }

        try {
            $response = $this->get('/etapas?nopaginate=true');

            if (($response['success'] ?? false) && isset($response['data'])) {
                $data = $response['data'];
                return isset($data['data']) && is_array($data['data']) ? $data['data'] : (is_array($data) ? $data : []);
            }

            return [];
        } catch (Exception $e) {
            Log::error('Error al obtener la lista de etapas: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene el catálogo de opciones de fuente de agua.
     *
     * @return array Opciones disponibles de fuente de agua
     */
    public function getFuenteAgua(): array
    {
        return $this->fetchOption('/configuracion/fuente-agua', 'fuente de agua');
    }

    /**
     * Obtiene el catálogo de opciones de tipo de explotación.
     *
     * @return array Opciones disponibles de tipo de explotación
     */
    public function getTipoExplotacion(): array
    {
        return $this->fetchOption('/configuracion/tipo-explotacion', 'tipo de explotación');
    }

    /**
     * Obtiene el catálogo de opciones de tipo de relieve.
     *
     * @return array Opciones disponibles de tipo de relieve
     */
    public function getTipoRelieve(): array
    {
        return $this->fetchOption('/configuracion/tipo-relieve', 'tipo de relieve');
    }

    /**
     * Obtiene el catálogo de opciones de textura de suelo.
     *
     * @return array Opciones disponibles de textura de suelo
     */
    public function getTexturaSuelo(): array
    {
        return $this->fetchOption('/configuracion/textura-suelo', 'textura de suelo');
    }

    /**
     * Obtiene el catálogo de opciones de pH de suelo.
     *
     * @return array Opciones disponibles de pH de suelo
     */
    public function getPhSuelo(): array
    {
        return $this->fetchOption('/configuracion/ph-suelo', 'pH de suelo');
    }

    /**
     * Obtiene el catálogo de opciones de método de riego.
     *
     * @return array Opciones disponibles de método de riego
     */
    public function getMetodoRiego(): array
    {
        return $this->fetchOption('/configuracion/metodo-riego', 'método de riego');
    }
}
