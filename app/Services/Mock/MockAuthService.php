<?php

namespace App\Services\Mock;

use App\Services\Contracts\AuthServiceInterface;

class MockAuthService implements AuthServiceInterface
{
    private const MOCK_USER = [
        'email' => 'admin@demo.cl',
        'password' => 'Password123!',
        'name' => 'Administrador GanaderaSoft',
        'role' => 'admin',
    ];

    /**
     * Attempt to authenticate a user with mock credentials
     */
    public function attempt(string $email, string $password): ?array
    {
        if ($email === self::MOCK_USER['email'] && $password === self::MOCK_USER['password']) {
            $userData = [
                'email' => self::MOCK_USER['email'],
                'name' => self::MOCK_USER['name'],
                'role' => self::MOCK_USER['role'],
                'token' => bin2hex(random_bytes(32)), // Simulated token
            ];

            // Store in session
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
}
