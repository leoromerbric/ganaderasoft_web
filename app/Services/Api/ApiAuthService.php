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
     * Intenta autenticar un usuario contra la API.
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
        
        // Solo llamamos al endpoint de logout si tenemos un token válido
        if (!empty($user['token'])) {
            $this->post('/auth/logout');
        }

        // Limpiar completamente la sesión de Laravel
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

        return [
            'id'          => $apiUser['id'] ?? null,
            'name'        => $apiUser['name'] ?? '',
            'email'       => $apiUser['email'] ?? '',
            'roles'       => $apiUser['roles'] ?? [],
            'status'      => $apiUser['status'] ?? 'active',
            'image'       => $apiUser['image'] ?? 'user.png',
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
}
