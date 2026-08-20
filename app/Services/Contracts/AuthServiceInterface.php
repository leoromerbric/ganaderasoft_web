<?php

namespace App\Services\Contracts;

interface AuthServiceInterface
{
    /**
     * Authenticate a user and create a session
     *
     * @return array|null Returns user data if successful, null otherwise
     */
    public function login(string $email, string $password): ?array;

    /**
     * Logout the current user
     */
    public function logout(): void;

    /**
     * Get the currently authenticated user
     */
    public function user(): ?array;

    /**
     * Get user profile details from API V2
     */
    public function getProfile(): ?array;

    /**
     * Actualizar la foto de perfil del usuario autenticado en la API.
     */
    public function updateProfilePhoto(\Illuminate\Http\UploadedFile $file): array;

    /**
     * Eliminar la foto de perfil del usuario autenticado en la API.
     */
    public function deleteProfilePhoto(): array;
}
