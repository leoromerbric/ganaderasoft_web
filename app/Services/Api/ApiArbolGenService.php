<?php

namespace App\Services\Api;

use App\Services\Contracts\ArbolGenServiceInterface;

class ApiArbolGenService extends BaseApiService implements ArbolGenServiceInterface
{
    private function authHeaders(): array
    {
        return [
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer ' . (session('user')['token'] ?? ''),
        ];
    }

    public function getArbol(int $animalId): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'data' => []];
        }

        return $this->get("/animales/{$animalId}/arbol", $this->authHeaders());
    }

    public function setProgenitor(int $animalId, array $data): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->post(
            "/animales/{$animalId}/progenitor",
            $data,
            $this->authHeaders() + ['Content-Type' => 'application/json']
        );
    }

    public function removeProgenitor(int $animalId, string $tipo): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->delete("/animales/{$animalId}/progenitor/{$tipo}", $this->authHeaders());
    }

    public function getDisponibles(int $animalId, string $tipo): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'data' => []];
        }

        return $this->get("/animales/{$animalId}/progenitores-disponibles?tipo={$tipo}", $this->authHeaders());
    }
}
