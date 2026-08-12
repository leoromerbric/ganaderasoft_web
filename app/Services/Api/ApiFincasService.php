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

        $response = $this->get('/fincas');

        return $response;
    }

    /**
     * Get a single finca by ID
     */
    public function getFinca(int $id): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->get("/fincas/{$id}");

        return $response;
    }

    /**
     * Create a new finca
     */
    public function createFinca(array $data): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->post('/fincas', $data);

        return $response;
    }

    /**
     * Update an existing finca
     */
    public function updateFinca(int $id, array $data): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->put("/fincas/{$id}", $data);

        return $response;
    }
}
