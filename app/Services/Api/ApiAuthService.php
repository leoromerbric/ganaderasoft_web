<?php

namespace App\Services\Api;

use App\Services\Contracts\AuthServiceInterface;

class ApiAuthService extends BaseApiService implements AuthServiceInterface
{
    /**
     * Attempt to authenticate a user with the API
     */
    public function attempt(string $email, string $password): ?array
    {
        $response = $this->post('/auth/login', [
            'email' => $email,
            'password' => $password,
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);

        if (isset($response['success']) && $response['success'] === true && isset($response['data'])) {
            $userData = [
                'id' => $response['data']['user']['id'],
                'name' => $response['data']['user']['name'],
                'email' => $response['data']['user']['email'],
                'type_user' => $response['data']['user']['type_user'],
                'image' => $response['data']['user']['image'] ?? 'user.png',
                'token' => $response['data']['token'],
                'token_type' => $response['data']['token_type'] ?? 'Bearer',
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
        $user = $this->user();
        
        if ($user && isset($user['token'])) {
            // Call the logout API endpoint
            $this->post('/auth/logout', [], [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $user['token'],
            ]);
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
}
