<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BaseApiService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'http://ec2-54-219-108-54.us-west-1.compute.amazonaws.com:9000/api';
    }

    /**
     * Make a GET request to the API
     */
    protected function get(string $endpoint, array $headers = []): array
    {
        try {
            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->get($this->baseUrl . $endpoint);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('API GET request failed', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'success' => false,
                'message' => 'Error al conectar con el servidor'
            ];
        } catch (\Exception $e) {
            Log::error('API GET request exception', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error de conexión: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Make a POST request to the API
     */
    protected function post(string $endpoint, array $data = [], array $headers = []): array
    {
        try {
            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->post($this->baseUrl . $endpoint, $data);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('API POST request failed', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'success' => false,
                'message' => 'Error al conectar con el servidor'
            ];
        } catch (\Exception $e) {
            Log::error('API POST request exception', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error de conexión: ' . $e->getMessage()
            ];
        }
    }
}
