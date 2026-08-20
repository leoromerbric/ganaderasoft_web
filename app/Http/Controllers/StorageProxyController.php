<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StorageProxyController extends Controller
{
    /**
     * Sirve archivos estáticos almacenados en el backend a través del frontend.
     * Esto evita problemas de resolución de nombres de contenedor (ERR_NAME_NOT_RESOLVED)
     * y problemas de CORS en el navegador del cliente.
     *
     * @param Request $request
     * @param string $path
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $path)
    {
        // Obtener la URL base del backend desde la configuración (ej: http://backend:80/api -> http://backend:80)
        $apiBaseUrl = config('services.api.base_url', env('API_BASE_URL', 'http://backend:80/api'));
        $backendHost = preg_replace('#/api/?$#', '', $apiBaseUrl);

        $targetUrl = rtrim($backendHost, '/') . '/storage/' . ltrim($path, '/');

        try {
            $response = Http::timeout(10)->get($targetUrl);

            if ($response->successful()) {
                $contentType = $response->header('Content-Type') ?? 'application/octet-stream';
                
                return response($response->body(), $response->status(), [
                    'Content-Type'  => $contentType,
                    'Cache-Control' => 'public, max-age=86400',
                    'ETag'          => $response->header('ETag', md5($response->body())),
                ]);
            }

            return response('Archivo no encontrado', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error('Error en StorageProxyController: ' . $e->getMessage(), ['targetUrl' => $targetUrl]);
            return response('Error al obtener archivo', Response::HTTP_BAD_GATEWAY);
        }
    }
}
