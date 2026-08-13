<?php

namespace App\Services\Api;

use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Support\Facades\Session;

/**
 * Servicio encargado de la autenticación con la API V2.
 */
class ApiAuthService extends BaseApiService implements AuthServiceInterface
{
    /**
     * Versión de API V2 especifica para el módulo de Autenticación (Patrón Estrangulador)
     */
    protected string $apiVersion = '2';

    /**
     * Intenta autenticar un usuario contra la API V2.
     *
     * @param string $email Correo electrónico del usuario.
     * @param string $password Contraseña del usuario.
     * @return array|null Datos del usuario y token si es exitoso, null en caso contrario.
     */
    public function attempt(string $email, string $password): ?array
    {
        $response = $this->post('/auth/login', [
            'email' => $email,
            'password' => $password,
        ]);

        if (empty($response['success']) || empty($response['data']['user'])) {
            return null;
        }

        $userData = $this->formatUserData($response['data']);
        $this->storeSession($userData);

        return $userData;
    }

    /**
     * Cierra la sesión del usuario actual tanto en la API como localmente.
     *
     * @return void
     */
    public function logout(): void
    {
        $user = $this->user();
        
        if ($user && !empty($user['token'])) {
            $this->post('/auth/logout');
        }

        Session::flush();
        Session::invalidate();
        Session::regenerateToken();
    }

    /**
     * Obtiene los datos del usuario actualmente autenticado desde la sesión.
     *
     * @return array|null
     */
    public function user(): ?array
    {
        return Session::get('authenticated') ? Session::get('user') : null;
    }

    /**
     * Formatea y extrae los datos relevantes de la respuesta de la API.
     *
     * @param array $apiData Los datos 'data' retornados por el login de la API V2.
     * @return array
     */
    private function formatUserData(array $apiData): array
    {
        $apiUser = $apiData['user'];
        $roles = $apiUser['roles'] ?? [];

        $typeUser = 'Tecnico';
        if (in_array('global_admin', $roles) || in_array('admin', $roles)) {
            $typeUser = 'Administrador';
        } elseif (in_array('propietario', $roles)) {
            $typeUser = 'Propietario';
        }

        return [
            'id'          => $apiUser['id'] ?? null,
            'name'        => $apiUser['name'] ?? '',
            'email'       => $apiUser['email'] ?? '',
            'roles'       => $roles,
            'type_user'   => $typeUser,
            'status'      => $apiUser['status'] ?? 'active',
            'image'       => $apiUser['image'] ?? 'user.png',
            'created_at'  => $apiUser['created_at'] ?? null,
            'propietario' => $apiUser['propietario'] ?? null,
            'token'       => $apiData['token'] ?? '',
            'token_type'  => $apiData['token_type'] ?? 'Bearer',
        ];
    }

    /**
     * Almacena los datos de autenticación en la sesión.
     *
     * @param array $userData
     * @return void
     */
    private function storeSession(array $userData): void
    {
        Session::put([
            'authenticated' => true,
            'user'          => $userData,
        ]);
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
