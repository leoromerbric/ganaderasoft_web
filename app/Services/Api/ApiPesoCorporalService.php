<?php

namespace App\Services\Api;

use App\Services\Contracts\PesoCorporalServiceInterface;

class ApiPesoCorporalService extends BaseApiService implements PesoCorporalServiceInterface
{
    /**
     * Get list of weight records
     */
    public function getPesosCorporales(?int $animalId = null, ?string $fechaInicio = null, ?string $fechaFin = null): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $endpoint = '/peso-corporal';
        $params = [];
        
        if ($animalId) {
            $params['animal_id'] = $animalId;
        }
        
        if ($fechaInicio) {
            $params['fecha_inicio'] = $fechaInicio;
        }
        
        if ($fechaFin) {
            $params['fecha_fin'] = $fechaFin;
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
     * Get a single weight record by ID
     */
    public function getPesoCorporal(int $id): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->get("/peso-corporal/{$id}", [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
        ]);

        return $response;
    }

    /**
     * Create a new weight record
     */
    public function createPesoCorporal(array $data): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->post('/peso-corporal', $data, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
            'Content-Type' => 'application/json',
        ]);

        return $response;
    }

    /**
     * Update an existing weight record
     */
    public function updatePesoCorporal(int $id, array $data): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->put("/peso-corporal/{$id}", $data, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
            'Content-Type' => 'application/json',
        ]);

        return $response;
    }

    /**
     * Delete a weight record
     */
    public function deletePesoCorporal(int $id): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->delete("/peso-corporal/{$id}", [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
        ]);

        return $response;
    }
}