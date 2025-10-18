<?php

namespace App\Services\Api;

use App\Services\Contracts\FincasServiceInterface;

class ApiFincasService extends BaseApiService implements FincasServiceInterface
{
    /**
     * Get list of fincas for authenticated user
     */
    public function getFincas(): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->get('/fincas', [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
        ]);

        return $response;
    }
}
