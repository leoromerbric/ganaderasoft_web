<?php

namespace App\Services\Api;

use App\Services\Contracts\LecheServiceInterface;

class ApiLecheService extends BaseApiService implements LecheServiceInterface
{
    /**
     * Get list of milk production records
     */
    public function getRegistrosLeche(?int $lactanciaId = null, ?string $fechaInicio = null, ?string $fechaFin = null): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $endpoint = '/leche';
        $params = [];
        
        if ($lactanciaId) {
            $params['lactancia_id'] = $lactanciaId;
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
     * Get a single milk production record by ID
     */
    public function getRegistroLeche(int $id): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->get("/leche/{$id}", [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
        ]);

        return $response;
    }

    /**
     * Create a new milk production record
     */
    public function createRegistroLeche(array $data): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->post('/leche', $data, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
            'Content-Type' => 'application/json',
        ]);

        return $response;
    }

    /**
     * Update an existing milk production record
     */
    public function updateRegistroLeche(int $id, array $data): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->put("/leche/{$id}", $data, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
            'Content-Type' => 'application/json',
        ]);

        return $response;
    }

    /**
     * Delete a milk production record
     */
    public function deleteRegistroLeche(int $id): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->delete("/leche/{$id}", [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
        ]);

        return $response;
    }
}