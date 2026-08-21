<?php

namespace App\Services\Api;

use App\Services\Contracts\ConfiguracionServiceInterface;

/**
 * Servicio encargado de gestionar las opciones de configuración y catálogos globales.
 */
class ApiConfiguracionService extends BaseApiService implements ConfiguracionServiceInterface
{
    /**
     * Obtiene el listado completo de etapas del sistema sin paginación.
     *
     * @return array
     */
    public function getEtapas(): array
    {
        $response = $this->get('/etapas' . $this->buildQuery([], true));
        return $this->extractCollection($response);
    }

    /**
     * Obtiene el catálogo de opciones de fuente de agua.
     *
     * @return array
     */
    public function getFuenteAgua(): array
    {
        return $this->get('/configuracion/fuente-agua');
    }

    /**
     * Obtiene el catálogo de opciones de tipo de explotación.
     *
     * @return array
     */
    public function getTipoExplotacion(): array
    {
        return $this->get('/configuracion/tipo-explotacion');
    }

    /**
     * Obtiene el catálogo de opciones de tipo de relieve.
     *
     * @return array
     */
    public function getTipoRelieve(): array
    {
        return $this->get('/configuracion/tipo-relieve');
    }

    /**
     * Obtiene el catálogo de opciones de textura de suelo.
     *
     * @return array
     */
    public function getTexturaSuelo(): array
    {
        return $this->get('/configuracion/textura-suelo');
    }

    /**
     * Obtiene el catálogo de opciones de pH de suelo.
     *
     * @return array
     */
    public function getPhSuelo(): array
    {
        return $this->get('/configuracion/ph-suelo');
    }

    /**
     * Obtiene el catálogo de opciones de método de riego.
     *
     * @return array
     */
    public function getMetodoRiego(): array
    {
        return $this->get('/configuracion/metodo-riego');
    }
}
