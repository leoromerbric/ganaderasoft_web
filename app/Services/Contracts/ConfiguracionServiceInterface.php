<?php

namespace App\Services\Contracts;

interface ConfiguracionServiceInterface
{
    /**
     * Get list of fuente agua options
     */
    public function getFuenteAgua(): array;

    /**
     * Get list of tipo explotacion options
     */
    public function getTipoExplotacion(): array;

    /**
     * Get list of tipo relieve options
     */
    public function getTipoRelieve(): array;

    /**
     * Get list of textura suelo options
     */
    public function getTexturaSuelo(): array;

    /**
     * Get list of ph suelo options
     */
    public function getPhSuelo(): array;

    /**
     * Get list of metodo riego options
     */
    public function getMetodoRiego(): array;
}
