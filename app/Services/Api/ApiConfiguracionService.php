<?php

namespace App\Services\Api;

use App\Services\Contracts\ConfiguracionServiceInterface;

class ApiConfiguracionService extends BaseApiService implements ConfiguracionServiceInterface
{
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

        $response = $this->get('/configuracion/fuente-agua', [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
        ]);

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

        $response = $this->get('/configuracion/tipo-explotacion', [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
        ]);

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

        $response = $this->get('/configuracion/tipo-relieve', [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
        ]);

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

        $response = $this->get('/configuracion/textura-suelo', [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
        ]);

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

        $response = $this->get('/configuracion/ph-suelo', [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
        ]);

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

        $response = $this->get('/configuracion/metodo-riego', [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
        ]);

        return $response;
    }
}
