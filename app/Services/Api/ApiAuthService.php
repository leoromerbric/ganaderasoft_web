<?php

namespace App\Services\Api;

use App\Services\Contracts\AuthServiceInterface;

class ApiAuthService extends BaseApiService implements AuthServiceInterface
{
    /**
     * Versión de API V2 especifica para el módulo de Autenticación (Patrón Estrangulador)
     */
    protected string $apiVersion = '2';

    /**
     * Attempt to authenticate a user with the API V2
     */
    public function attempt(string $email, string $password): ?array
    {
        $response = $this->post('/auth/login', [
            'email' => $email,
            'password' => $password,
        ]);

        if (isset($response['success']) && $response['success'] === true && isset($response['data']['user'])) {
            $userResource = $response['data']['user'];
            $roles = $userResource['roles'] ?? [];

            // Determinar etiqueta legible para compatibilidad
            $typeUser = 'Tecnico';
            if (in_array('global_admin', $roles) || in_array('admin', $roles)) {
                $typeUser = 'Administrador';
            } elseif (in_array('propietario', $roles)) {
                $typeUser = 'Propietario';
            }

            $userData = [
                'id' => $userResource['id'] ?? null,
                'name' => $userResource['name'] ?? '',
                'email' => $userResource['email'] ?? '',
                'status' => $userResource['status'] ?? 'activo',
                'roles' => $roles,
                'type_user' => $typeUser,
                'image' => $userResource['image'] ?? 'user.png',
                'created_at' => $userResource['created_at'] ?? null,
                'propietario' => $userResource['propietario'] ?? null,
                'token' => $response['data']['token'] ?? null,
                'token_type' => $response['data']['token_type'] ?? 'Bearer',
            ];

            // Guardar en sesión
            session([
                'authenticated' => true,
                'user' => $userData,
            ]);

            return $userData;
        }

        return null;
    }

    /**
     * Logout the current user
     */
    public function logout(): void
    {
        $user = $this->user();
        
        if ($user && isset($user['token'])) {
            $this->post('/auth/logout');
        }

        session()->forget('authenticated');
        session()->forget('user');
        session()->invalidate();
        session()->regenerateToken();
    }

    /**
     * Get the currently authenticated user
     */
    public function user(): ?array
    {
        if (session('authenticated')) {
            return session('user');
        }

        return null;
    }

    /**
     * Get user profile details from API V2
     */
    public function getProfile(): ?array
    {
        $response = $this->get('/profile');

        if (isset($response['success']) && $response['success'] === true && isset($response['data']['user'])) {
            $userResource = $response['data']['user'];
            $roles = $userResource['roles'] ?? [];

            $typeUser = 'Tecnico';
            if (in_array('global_admin', $roles) || in_array('admin', $roles)) {
                $typeUser = 'Administrador';
            } elseif (in_array('propietario', $roles)) {
                $typeUser = 'Propietario';
            }

            $currentUser = session('user') ?? [];

            $userData = array_merge($currentUser, [
                'id' => $userResource['id'] ?? null,
                'name' => $userResource['name'] ?? '',
                'email' => $userResource['email'] ?? '',
                'status' => $userResource['status'] ?? 'activo',
                'roles' => $roles,
                'type_user' => $typeUser,
                'created_at' => $userResource['created_at'] ?? null,
                'email_verified_at' => $userResource['email_verified_at'] ?? null,
                'propietario' => $userResource['propietario'] ?? null,
            ]);

            session(['user' => $userData]);

            return $userData;
        }

        return $this->user();
    }
}
