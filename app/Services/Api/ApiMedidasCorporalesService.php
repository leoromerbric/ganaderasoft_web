<?php

namespace App\Services\Api;

use App\Services\Contracts\MedidasCorporalesServiceInterface;

class ApiMedidasCorporalesService extends BaseApiService implements MedidasCorporalesServiceInterface
{
    /**
     * Get list of body measurements
     */
    public function getMedidasCorporales(?int $animalId = null, ?int $etapaId = null): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $endpoint = '/medidas-corporales';
        $params = [];
        
        if ($animalId) {
            $params['animal_id'] = $animalId;
        }
        
        if ($etapaId) {
            $params['etapa_id'] = $etapaId;
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
     * Get a single body measurement record by ID
     */
    public function getMedidaCorporal(int $id): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->get("/medidas-corporales/{$id}", [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
        ]);

        return $response;
    }

    /**
     * Create a new body measurement record
     */
    public function createMedidaCorporal(array $data): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->post('/medidas-corporales', $data, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
            'Content-Type' => 'application/json',
        ]);

        return $response;
    }

    /**
     * Update an existing body measurement record
     */
    public function updateMedidaCorporal(int $id, array $data): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->put("/medidas-corporales/{$id}", $data, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
            'Content-Type' => 'application/json',
        ]);

        return $response;
    }

    /**
     * Delete a body measurement record
     */
    public function deleteMedidaCorporal(int $id): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->delete("/medidas-corporales/{$id}", [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
        ]);

        return $response;
    }
}