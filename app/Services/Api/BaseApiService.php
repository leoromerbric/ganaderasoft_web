<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Servicio base abstracto para el consumo de la API Backend V2.
 * Proporciona métodos HTTP estandarizados, inyección de tokens de sesión,
 * logging inteligente de errores, utilidades de query string y extracción de datos.
 */
abstract class BaseApiService
{
    /**
     * URL base de la API backend.
     */
    protected string $baseUrl;

    /**
     * Timeout en segundos para las peticiones HTTP.
     */
    protected int $timeout;

    /**
     * Inicializa el servicio configurando la URL base y timeout desde la configuración.
     */
    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.api.base_url', env('API_BASE_URL', 'http://ganaderasoft-backend/api')), '/');
        $this->timeout = (int) config('services.api.timeout', env('API_TIMEOUT', 15));
    }

    /**
     * Genera las cabeceras por defecto para todas las peticiones a la API.
     * Inyecta automáticamente el token de autorización si el usuario tiene sesión activa,
     * la versión de la API (X-API-VERSION: 2) y el formato JSON.
     *
     * @param array $customHeaders Cabeceras adicionales que sobrescribirán las por defecto.
     * @param bool $isJson Si es true incluye Content-Type application/json.
     * @return array Arreglo final de cabeceras.
     */
    protected function defaultHeaders(array $customHeaders = [], bool $isJson = true): array
    {
        $headers = [
            'Accept'        => 'application/json',
            'X-API-VERSION' => '2',
        ];

        if ($isJson) {
            $headers['Content-Type'] = 'application/json';
        }

        if (session()->has('user.token')) {
            $headers['Authorization'] = 'Bearer ' . session('user.token');
        }

        return array_merge($headers, $customHeaders);
    }

    /**
     * Construye una cadena de consulta (query string) limpia a partir de un arreglo de parámetros,
     * omitiendo valores nulos o cadenas vacías.
     *
     * @param array $params Parámetros clave-valor.
     * @param bool $defaultNoPaginate Si es true y no viene especificado 'nopaginate', añade 'nopaginate=true'.
     * @return string Query string comenzando con '?' o cadena vacía.
     */
    protected function buildQuery(array $params = [], bool $defaultNoPaginate = false): string
    {
        $clean = array_filter($params, static function ($v) {
            return $v !== null && $v !== '';
        });

        if ($defaultNoPaginate && !isset($clean['nopaginate']) && !isset($clean['page'])) {
            $clean['nopaginate'] = 'true';
        }

        return !empty($clean) ? '?' . http_build_query($clean) : '';
    }

    /**
     * Extrae de forma segura una colección/listado de elementos desde la respuesta de la API V2,
     * dando soporte tanto a respuestas paginadas (data.data) como a colecciones directas (data).
     *
     * @param array $response Respuesta estructurada de la API.
     * @return array Arreglo de elementos extraídos o arreglo vacío si no hay datos.
     */
    protected function extractCollection(array $response): array
    {
        $data = $response['data'] ?? [];

        if (is_array($data)) {
            if (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) {
                return $data['data'];
            }
            return $data;
        }

        return [];
    }

    /**
     * Extrae de forma segura el registro único (item/detalle) de una respuesta de la API.
     *
     * @param array $response Respuesta de la API.
     * @return array|null Datos del registro o null si no se encontró.
     */
    protected function extractItem(array $response): ?array
    {
        if (!($response['success'] ?? false) || !isset($response['data']) || !is_array($response['data'])) {
            return null;
        }

        return $response['data'];
    }

    /**
     * Estandariza el formato de respuesta cuando la API devuelve un error (ej: 400, 422, 500).
     * Extrae el primer mensaje de validación si existe, o devuelve un mensaje por defecto.
     *
     * @param \Illuminate\Http\Client\Response $response Respuesta de la API.
     * @param string $defaultMessage Mensaje de error genérico.
     * @return array Arreglo estructurado indicando el fallo.
     */
    private function formatApiFailure($response, string $defaultMessage): array
    {
        $json = $response->json();

        if (is_array($json)) {
            $message = $json['message'] ?? $defaultMessage;

            // Extraer el primer error de validación si existe un arreglo de errores
            if (!empty($json['errors']) && is_array($json['errors'])) {
                $first = collect($json['errors'])->flatten()->first();
                if (is_string($first) && $first !== '') {
                    $message = $first;
                }
            }

            return [
                'success' => false,
                'message' => $message,
                'errors'  => $json['errors'] ?? null,
                'status'  => $response->status(),
            ];
        }

        return [
            'success' => false,
            'message' => $defaultMessage,
            'status'  => $response->status(),
        ];
    }

    /**
     * Método centralizado para ejecutar las peticiones HTTP y manejar sus excepciones.
     *
     * @param string $method Método HTTP (get, post, put, patch, delete).
     * @param string $endpoint Ruta relativa del endpoint.
     * @param array $data Cuerpo de la petición (opcional).
     * @param array $headers Cabeceras personalizadas (opcional).
     * @return array Respuesta estructurada.
     */
    private function sendRequest(string $method, string $endpoint, array $data = [], array $headers = []): array
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

        try {
            $request = Http::withHeaders($this->defaultHeaders($headers))->timeout($this->timeout);

            $response = empty($data)
                ? $request->{$method}($url)
                : $request->{$method}($url, $data);

            if ($response->successful()) {
                $json = $response->json();
                return is_array($json) ? $json : ['success' => true, 'data' => $json];
            }

            // Logging de errores del servidor
            if ($response->serverError()) {
                Log::error("API " . strtoupper($method) . " request failed (Server Error)", [
                    'endpoint' => $endpoint,
                    'status'   => $response->status(),
                    'body'     => $response->body(),
                ]);
            } elseif ($response->clientError() && !in_array($response->status(), [401, 422], true)) {
                Log::warning("API " . strtoupper($method) . " request failed (Client Error)", [
                    'endpoint' => $endpoint,
                    'status'   => $response->status(),
                ]);
            }

            return $this->formatApiFailure($response, 'Error al conectar con el servidor');
        } catch (\Exception $e) {
            Log::error("API " . strtoupper($method) . " request exception", [
                'endpoint' => $endpoint,
                'error'    => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Error de conexión: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Realiza una petición GET a la API.
     *
     * @param string $endpoint Ruta del endpoint.
     * @param array $headers Cabeceras adicionales.
     * @return array
     */
    protected function get(string $endpoint, array $headers = []): array
    {
        return $this->sendRequest('get', $endpoint, [], $headers);
    }

    /**
     * Realiza una petición POST a la API.
     *
     * @param string $endpoint Ruta del endpoint.
     * @param array $data Datos a enviar en el cuerpo de la petición.
     * @param array $headers Cabeceras adicionales.
     * @return array
     */
    protected function post(string $endpoint, array $data = [], array $headers = []): array
    {
        return $this->sendRequest('post', $endpoint, $data, $headers);
    }

    /**
     * Realiza una petición PUT a la API.
     *
     * @param string $endpoint Ruta del endpoint.
     * @param array $data Datos a enviar en el cuerpo de la petición.
     * @param array $headers Cabeceras adicionales.
     * @return array
     */
    protected function put(string $endpoint, array $data = [], array $headers = []): array
    {
        return $this->sendRequest('put', $endpoint, $data, $headers);
    }

    /**
     * Realiza una petición PATCH a la API.
     *
     * @param string $endpoint Ruta del endpoint.
     * @param array $data Datos a enviar en el cuerpo de la petición.
     * @param array $headers Cabeceras adicionales.
     * @return array
     */
    protected function patch(string $endpoint, array $data = [], array $headers = []): array
    {
        return $this->sendRequest('patch', $endpoint, $data, $headers);
    }

    /**
     * Realiza una petición DELETE a la API.
     *
     * @param string $endpoint Ruta del endpoint.
     * @param array $headers Cabeceras adicionales.
     * @return array
     */
    protected function delete(string $endpoint, array $headers = []): array
    {
        return $this->sendRequest('delete', $endpoint, [], $headers);
    }

    /**
     * Realiza una petición POST con archivos multipart a la API.
     *
     * @param string $endpoint Ruta del endpoint.
     * @param array $data Campos de texto a enviar.
     * @param array $files Arreglo de archivos en formato ['campo' => UploadedFile].
     * @param array $headers Cabeceras adicionales.
     * @return array
     */
    protected function postMultipart(string $endpoint, array $data = [], array $files = [], array $headers = []): array
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

        try {
            $http = Http::withHeaders($this->defaultHeaders($headers, false))->timeout(60);

            foreach ($files as $name => $file) {
                if ($file instanceof \Illuminate\Http\UploadedFile) {
                    $http->attach($name, file_get_contents($file->getRealPath()), $file->getClientOriginalName());
                } elseif (is_array($file) && isset($file['path'])) {
                    $http->attach($name, file_get_contents($file['path']), $file['name'] ?? basename($file['path']));
                }
            }

            $response = $http->post($url, $data);

            if ($response->successful()) {
                $json = $response->json();
                return is_array($json) ? $json : ['success' => true, 'data' => $json];
            }

            if ($response->serverError()) {
                Log::error("API POST Multipart request failed (Server Error)", [
                    'endpoint' => $endpoint,
                    'status'   => $response->status(),
                    'body'     => $response->body(),
                ]);
            }

            return $this->formatApiFailure($response, 'Error al procesar el archivo');
        } catch (\Exception $e) {
            Log::error("API POST Multipart request exception", [
                'endpoint' => $endpoint,
                'error'    => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Error de conexión: ' . $e->getMessage(),
            ];
        }
    }
}
