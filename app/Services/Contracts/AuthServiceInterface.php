<?php

namespace App\Services\Contracts;

interface AuthServiceInterface
{
    /**
     * Attempt to authenticate a user
     *
     * @return array|null Returns user data if successful, null otherwise
     */
    public function attempt(string $email, string $password): ?array;

    /**
     * Logout the current user
     */
    public function logout(): void;

    /**
     * Get the currently authenticated user
     */
    public function user(): ?array;
}
