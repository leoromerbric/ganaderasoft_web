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
     * Inicia sesión autenticando al usuario contra la API V2, guarda el token
     * y enriquece la sesión con los datos completos del perfil.
     *
     * @param string $email Correo electrónico del usuario.
     * @param string $password Contraseña del usuario.
     * @return array|null Datos del usuario y token si es exitoso, null en caso contrario.
     */
    public function login(string $email, string $password): ?array
    {
        $response = $this->post('/auth/login', [
            'email'    => $email,
            'password' => $password,
        ]);

        if (empty($response['success']) || empty($response['data']['user'])) {
            if (!empty($response['message'])) {
                session()->flash('auth_error', $response['message']);
            }
            return null;
        }

        // 1. Guardar la sesión inicial con el token obtenido
        $userData = $this->formatUserData(
            $response['data']['user'],
            $response['data']['token'] ?? '',
            $response['data']['token_type'] ?? 'Bearer'
        );
        $this->storeSession($userData);

        //  Enriquecer la sesión con los detalles completos del perfil (/profile)
        return $this->getProfile() ?? $userData;
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
     * Obtiene y actualiza los detalles del perfil del usuario autenticado desde la API V2.
     *
     * @return array|null
     */
    public function getProfile(): ?array
    {
        $response = $this->get('/profile');

        if (empty($response['success']) || empty($response['data']['user'])) {
            return $this->user();
        }

        // Formatear datos del usuario preservando el token de la sesión activa
        $userData = $this->formatUserData($response['data']['user']);
        $this->storeSession($userData);

        return $userData;
    }

    /**
     * Formateador único para estandarizar los datos del usuario retornados por la API V2.
     *
     * @param array $apiUser Datos del usuario provenientes de la API.
     * @param string|null $token Token de autenticación (opcional si ya existe en sesión).
     * @param string|null $tokenType Tipo de token (opcional si ya existe en sesión).
     * @return array
     */
    private function formatUserData(array $apiUser, ?string $token = null, ?string $tokenType = null): array
    {
        $rolesCollection = collect($apiUser['roles'] ?? []);

        // 1. Arreglo plano de códigos de rol (ej: ['global_admin', 'propietario'])
        $roleCodes = $rolesCollection->map(function ($r) {
            return is_array($r) ? ($r['code'] ?? '') : (string)$r;
        })->filter()->values()->all();

        // 2. Arreglo plano de permisos únicos (ej: ['usuario.read', 'finca.read'])
        $permissions = $rolesCollection->pluck('permissions')
            ->flatten()
            ->filter()
            ->unique()
            ->values()
            ->all();

        // 3. Detalle completo de roles para vistas de perfil
        $rolesDetail = $rolesCollection->all();

        return [
            'id'                => $apiUser['id'] ?? null,
            'name'              => $apiUser['name'] ?? '',
            'email'             => $apiUser['email'] ?? '',
            'status'            => $apiUser['status'] ?? 'active',
            'roles'             => $roleCodes,
            'permissions'       => $permissions,
            'roles_detail'      => $rolesDetail,
            'type_user'         => $this->resolveUserType($roleCodes),
            'foto'              => $apiUser['foto'] ?? null,
            'avatar'            => $apiUser['avatar'] ?? $apiUser['foto'] ?? null,
            'profile_photo_url' => $apiUser['profile_photo_url'] ?? $apiUser['foto'] ?? null,
            'image'             => $apiUser['avatar'] ?? $apiUser['foto'] ?? 'user.png',
            'created_at'        => $apiUser['created_at'] ?? null,
            'email_verified_at' => $apiUser['email_verified_at'] ?? null,
            'persona'           => $apiUser['persona'] ?? null,
            'propietario'       => $apiUser['propietario'] ?? null,
            'token'             => $token ?? session('user.token') ?? '',
            'token_type'        => $tokenType ?? session('user.token_type') ?? 'Bearer',
        ];
    }

    /**
     * Actualiza la foto de perfil del usuario autenticado en la API.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return array
     */
    public function updateProfilePhoto(\Illuminate\Http\UploadedFile $file): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        $response = $this->postMultipart(
            '/profile/photo',
            [],
            ['foto' => $file]
        );

        if (!empty($response['success']) && !empty($response['data']['user'])) {
            $userData = $this->formatUserData($response['data']['user']);
            $this->storeSession($userData);
        }

        return $response;
    }

    /**
     * Elimina la foto de perfil del usuario autenticado en la API.
     *
     * @return array
     */
    public function deleteProfilePhoto(): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        $response = $this->delete('/profile/photo');

        if (!empty($response['success']) && !empty($response['data']['user'])) {
            $userData = $this->formatUserData($response['data']['user']);
            $this->storeSession($userData);
        }

        return $response;
    }

    /**
     * Determina la etiqueta del tipo de usuario principal según sus roles.
     *
     * @param array $roles
     * @return string
     */
    private function resolveUserType(array $roles): string
    {
        if (in_array('global_admin', $roles, true) || in_array('admin', $roles, true)) {
            return 'Administrador';
        }

        if (in_array('propietario', $roles, true)) {
            return 'Propietario';
        }

        return 'Empleado';
    }

    /**
     * Almacena los datos de autenticación en la sesión de Laravel.
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
