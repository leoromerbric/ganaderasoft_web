<?php

namespace App\Services\Api;

use App\Services\Contracts\LactanciaServiceInterface;

class ApiLactanciaService extends BaseApiService implements LactanciaServiceInterface
{
    /**
     * Get list of lactation periods
     */
    public function getLactancias(?int $animalId = null, ?bool $activa = null, ?string $fechaInicio = null, ?string $fechaFin = null): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $endpoint = '/lactancia';
        $params = [];
        
        if ($animalId) {
            $params['animal_id'] = $animalId;
        }
        
        if ($activa !== null) {
            $params['activa'] = $activa ? 1 : 0;
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

        $response = $this->get($endpoint);

        return $response;
    }

    /**
     * Get a single lactation period by ID
     */
    public function getLactancia(int $id): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->get("/lactancia/{$id}");

        return $response;
    }

    /**
     * Create a new lactation period
     */
    public function createLactancia(array $data): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->post('/lactancia', $data);

        return $response;
    }

    /**
     * Update an existing lactation period
     */
    public function updateLactancia(int $id, array $data): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->put("/lactancia/{$id}", $data);

        return $response;
    }

    /**
     * Delete a lactation period
     */
    public function deleteLactancia(int $id): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->delete("/lactancia/{$id}");

        return $response;
    }
}