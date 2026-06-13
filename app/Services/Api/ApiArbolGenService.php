<?php

namespace App\Services\Api;

use App\Services\Contracts\ArbolGenServiceInterface;

class ApiArbolGenService extends BaseApiService implements ArbolGenServiceInterface
{
    private function getUser(): ?array
    {
        $user = session('user');
        return ($user && isset($user['token'])) ? $user : null;
    }

    private function authHeaders(array $user): array
    {
        return [
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer ' . $user['token'],
        ];
    }

    public function getArbol(int $animalId): array
    {
        $user = $this->getUser();
        if (!$user) {
            return ['success' => false, 'data' => []];
        }

        return $this->get("/animales/{$animalId}/arbol", $this->authHeaders($user));
    }

    public function setProgenitor(int $animalId, array $data): array
    {
        $user = $this->getUser();
        if (!$user) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->post(
            "/animales/{$animalId}/progenitor",
            $data,
            $this->authHeaders($user) + ['Content-Type' => 'application/json']
        );
    }

    public function removeProgenitor(int $animalId, string $tipo): array
    {
        $user = $this->getUser();
        if (!$user) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->delete("/animales/{$animalId}/progenitor/{$tipo}", $this->authHeaders($user));
    }

    public function getDisponibles(int $animalId, string $tipo): array
    {
        $user = $this->getUser();
        if (!$user) {
            return ['success' => false, 'data' => []];
        }

        return $this->get("/animales/{$animalId}/progenitores-disponibles?tipo={$tipo}", $this->authHeaders($user));
    }
}
