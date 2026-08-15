<?php

namespace App\Services\Contracts;

/**
 * Interfaz para el servicio de configuración y catálogos globales del sistema.
 */
interface ConfiguracionServiceInterface
{
    /**
     * Obtiene el listado de etapas de desarrollo del sistema.
     *
     * @return array
     */
    public function getEtapas(): array;

    /**
     * Obtiene las opciones del catálogo de fuentes de agua.
     *
     * @return array
     */
    public function getFuenteAgua(): array;

    /**
     * Obtiene las opciones del catálogo de tipos de explotación.
     *
     * @return array
     */
    public function getTipoExplotacion(): array;

    /**
     * Obtiene las opciones del catálogo de tipos de relieve.
     *
     * @return array
     */
    public function getTipoRelieve(): array;

    /**
     * Obtiene las opciones del catálogo de texturas de suelo.
     *
     * @return array
     */
    public function getTexturaSuelo(): array;

    /**
     * Obtiene las opciones del catálogo de valores de pH de suelo.
     *
     * @return array
     */
    public function getPhSuelo(): array;

    /**
     * Obtiene las opciones del catálogo de métodos de riego.
     *
     * @return array
     */
    public function getMetodoRiego(): array;
}
