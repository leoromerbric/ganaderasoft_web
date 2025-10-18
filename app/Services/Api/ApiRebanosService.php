<?php

namespace App\Services\Api;

use App\Services\Contracts\RebanosServiceInterface;

class ApiRebanosService extends BaseApiService implements RebanosServiceInterface
{
    /**
     * Get list of rebaños for authenticated user
     */
    public function getRebanos(): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->get('/rebanos', [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
        ]);

        return $response;
    }
}
