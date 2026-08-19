<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BaseApiService
{
    /**
     * URL base de la API, obtenida de las variables de entorno.
     */
    protected string $baseUrl;

    /**
     * Inicializa el servicio configurando la URL base.
     */
    public function __construct()
    {
        $this->baseUrl = env('API_BASE_URL', 'http://ec2-54-219-108-54.us-west-1.compute.amazonaws.com:9000/api');
    }

    /**
     * Genera las cabeceras por defecto para todas las peticiones a la API.
     * Inyecta automáticamente el token de autorización si el usuario tiene sesión.
     *
     * @param array $customHeaders Cabeceras adicionales que sobrescribirán las por defecto.
     * @return array Arreglo final de cabeceras.
     */
    protected function defaultHeaders(array $customHeaders = []): array
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-API-VERSION' => '2',
        ];

        if (session()->has('user.token')) {
            $headers['Authorization'] = 'Bearer ' . session('user.token');
        }

        return array_merge($headers, $customHeaders);
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

    /**
     * Método centralizado para ejecutar las peticiones HTTP y manejar sus excepciones.
     * Reduce la duplicación de código en los métodos GET, POST, PUT y DELETE.
     *
     * @param string $method Método HTTP (get, post, put, delete).
     * @param string $endpoint Ruta relativa del endpoint.
     * @param array $data Cuerpo de la petición (opcional).
     * @param array $headers Cabeceras personalizadas (opcional).
     * @return array Respuesta estructurada.
     */
    private function sendRequest(string $method, string $endpoint, array $data = [], array $headers = []): array
    {
        try {
            $request = Http::withHeaders($this->defaultHeaders($headers))->timeout(10);
            
            // Los métodos GET y DELETE no envían el cuerpo ($data) de la misma forma en Laravel Http,
            // pero para simplificar, si hay data la pasamos (aunque para GET/DELETE suele estar vacía).
            $response = empty($data) 
                ? $request->{$method}($this->baseUrl . $endpoint)
                : $request->{$method}($this->baseUrl . $endpoint, $data);

            if ($response->successful()) {
                return $response->json();
            }

            // Registrar errores en los logs de forma inteligente para no ensuciarlos con validaciones fallidas
            if ($response->serverError()) {
                // Solo logueamos como error crítico cuando el backend falla (5xx)
                Log::error("API " . strtoupper($method) . " request failed (Server Error)", [
                    'endpoint' => $endpoint,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            } elseif ($response->clientError() && !in_array($response->status(), [401, 422])) {
                // Advertencias para otros errores (ej. 403, 404)
                Log::warning("API " . strtoupper($method) . " request failed (Client Error)", [
                    'endpoint' => $endpoint,
                    'status' => $response->status()
                ]);
            }

            return $this->formatApiFailure($response, 'Error al conectar con el servidor');
            
        } catch (\Exception $e) {
            Log::error("API " . strtoupper($method) . " request exception", [
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
        try {
            $reqHeaders = [
                'Accept' => 'application/json',
                'X-API-VERSION' => '2',
            ];
            if (session()->has('user.token')) {
                $reqHeaders['Authorization'] = 'Bearer ' . session('user.token');
            }
            $reqHeaders = array_merge($reqHeaders, $headers);

            $http = Http::withHeaders($reqHeaders)->timeout(60);

            foreach ($files as $name => $file) {
                if ($file instanceof \Illuminate\Http\UploadedFile) {
                    $http->attach($name, file_get_contents($file->getRealPath()), $file->getClientOriginalName());
                } elseif (is_array($file)) {
                    $http->attach($name, file_get_contents($file['path']), $file['name'] ?? basename($file['path']));
                }
            }

            $response = $http->post($this->baseUrl . $endpoint, $data);

            if ($response->successful()) {
                return $response->json();
            }

            if ($response->serverError()) {
                Log::error("API POST Multipart request failed (Server Error)", [
                    'endpoint' => $endpoint,
                    'status'   => $response->status(),
                    'body'     => $response->body()
                ]);
            }

            return $this->formatApiFailure($response, 'Error al procesar el archivo');
        } catch (\Exception $e) {
            Log::error("API POST Multipart request exception", [
                'endpoint' => $endpoint,
                'error'    => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error de conexión: ' . $e->getMessage()
            ];
        }
    }
}
