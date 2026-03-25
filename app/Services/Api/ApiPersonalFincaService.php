<?php

namespace App\Services\Api;

use App\Services\Contracts\PersonalFincaServiceInterface;

class ApiPersonalFincaService extends BaseApiService implements PersonalFincaServiceInterface
{
    /**
     * Get list of personal de finca
     */
    public function getPersonalFinca(?int $fincaId = null): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $endpoint = '/personal-finca';
        $params = [];
        
        if ($fincaId) {
            $params['id_finca'] = $fincaId;
        }

        if (!empty($params)) {
            $endpoint .= '?' . http_build_query($params);
        }

        $response = $this->get($endpoint, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
        ]);

        return $response;
    }

    /**
     * Get a single personal de finca record by ID
     */
    public function getPersonalFincaById(int $id): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->get("/personal-finca/{$id}", [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
        ]);

        return $response;
    }

    /**
     * Create a new personal de finca record
     */
    public function createPersonalFinca(array $data): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->post('/personal-finca', $data, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
            'Content-Type' => 'application/json',
        ]);

        return $response;
    }

    /**
     * Update an existing personal de finca record
     */
    public function updatePersonalFinca(int $id, array $data): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->put("/personal-finca/{$id}", $data, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
            'Content-Type' => 'application/json',
        ]);

        return $response;
    }

    /**
     * Delete a personal de finca record
     */
    public function deletePersonalFinca(int $id): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->delete("/personal-finca/{$id}", [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
        ]);

        return $response;
    }
}