<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BaseApiService
{
    protected string $baseUrl;

    private function formatApiFailure($response, string $defaultMessage): array
    {
        $json = $response->json();

        if (is_array($json)) {
            $message = $json['message'] ?? $defaultMessage;

            if (!empty($json['errors']) && is_array($json['errors'])) {
                $first = collect($json['errors'])->flatten()->first();
                if (is_string($first) && $first !== '') {
                    $message = $first;
                }
            }

            return [
                'success' => false,
                'message' => $message,
                'errors' => $json['errors'] ?? null,
                'status' => $response->status(),
            ];
        }

        return [
            'success' => false,
            'message' => $defaultMessage,
            'status' => $response->status(),
        ];
    }

    public function __construct()
    {
        $this->baseUrl = env('API_BASE_URL', 'http://ec2-54-219-108-54.us-west-1.compute.amazonaws.com:9000/api');
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

            return $this->formatApiFailure($response, 'Error al conectar con el servidor');
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

            return $this->formatApiFailure($response, 'Error al conectar con el servidor');
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

    /**
     * Make a PUT request to the API
     */
    protected function put(string $endpoint, array $data = [], array $headers = []): array
    {
        try {
            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->put($this->baseUrl . $endpoint, $data);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('API PUT request failed', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return $this->formatApiFailure($response, 'Error al conectar con el servidor');
        } catch (\Exception $e) {
            Log::error('API PUT request exception', [
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
     * Make DELETE request to API
     */
    protected function delete(string $endpoint, array $headers = []): array
    {
        try {
            $url = rtrim(env('API_BASE_URL', 'http://localhost:8000/api'), '/') . $endpoint;
            
            $response = Http::withHeaders($headers)->delete($url);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('API DELETE request failed', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return $this->formatApiFailure($response, 'Error al conectar con el servidor');
        } catch (\Exception $e) {
            Log::error('API DELETE request exception', [
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
