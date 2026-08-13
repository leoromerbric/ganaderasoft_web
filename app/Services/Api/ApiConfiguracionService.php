<?php

namespace App\Services\Api;

use App\Services\Contracts\ConfiguracionServiceInterface;

class ApiConfiguracionService extends BaseApiService implements ConfiguracionServiceInterface
{
    /**
     * Get list of etapas options
     */
    public function getEtapas(): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [];
        }

        $response = $this->get('/etapas');

        if (isset($response['success']) && $response['success']) {
            return $response['data'] ?? [];
        }

        return [];
    }

    /**
     * Get list of fuente agua options
     */
    public function getFuenteAgua(): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->get('/configuracion/fuente-agua');

        return $response;
    }

    /**
     * Get list of tipo explotacion options
     */
    public function getTipoExplotacion(): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->get('/configuracion/tipo-explotacion');

        return $response;
    }

    /**
     * Get list of tipo relieve options
     */
    public function getTipoRelieve(): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->get('/configuracion/tipo-relieve');

        return $response;
    }

    /**
     * Get list of textura suelo options
     */
    public function getTexturaSuelo(): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->get('/configuracion/textura-suelo');

        return $response;
    }

    /**
     * Get list of ph suelo options
     */
    public function getPhSuelo(): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->get('/configuracion/ph-suelo');

        return $response;
    }

    /**
     * Get list of metodo riego options
     */
    public function getMetodoRiego(): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->get('/configuracion/metodo-riego');

        return $response;
    }
}
