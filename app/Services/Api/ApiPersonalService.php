<?php

namespace App\Services\Api;

use App\Services\Contracts\PersonalServiceInterface;

class ApiPersonalService extends BaseApiService implements PersonalServiceInterface
{
    /**
     * Get list of personal for a given finca
     */
    public function getPersonal(int $idFinca): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->get('/personal-finca?id_finca=' . $idFinca);

        return $response;
    }

    /**
     * Create new personal for a finca
     */
    public function createPersonal(array $data): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->post('/personal-finca', $data);

        return $response;
    }

    /**
     * Update existing personal
     */
    public function updatePersonal(int $id, array $data): array
    {
        $user = session('user');
        
        if (!$user || !isset($user['token'])) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado'
            ];
        }

        $response = $this->put('/personal-finca/' . $id, $data);

        return $response;
    }
}
