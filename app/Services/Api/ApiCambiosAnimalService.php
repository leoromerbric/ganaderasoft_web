<?php

namespace App\Services\Api;

use App\Services\Contracts\CambiosAnimalServiceInterface;
use Exception;

class ApiCambiosAnimalService extends BaseApiService implements CambiosAnimalServiceInterface
{
    /**
     * Obtiene la lista de cambios de animales con filtros opcionales
     * 
     * @param int|null $idAnimal Filtro por animal
     * @param int|null $idFinca Filtro por finca
     * @return array Lista de cambios de animales
     */
    public function getList(?int $idAnimal = null, ?int $idFinca = null): array
    {
        try {
            $params = [];
            
            if ($idAnimal) {
                $params['animal_id'] = $idAnimal;
            }
            
            if ($idFinca) {
                $params['finca_id'] = $idFinca;
            }
            
            $queryString = !empty($params) ? '?' . http_build_query($params) : '';
            $response = $this->makeRequest('GET', "cambios-animal{$queryString}");
            
            if ($response['success']) {
                return $response['data'] ?? [];
            }
            
            return [];
        } catch (Exception $e) {
            \Log::error('Error obteniendo cambios de animales: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Crea un nuevo registro de cambio de animal
     * 
     * @param array $data Datos del cambio
     * @return array Respuesta de la API
     */
    public function create(array $data): array
    {
        try {
            $response = $this->makeRequest('POST', 'cambios-animal', $data);
            return $response;
        } catch (Exception $e) {
            \Log::error('Error creando cambio de animal: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtiene los detalles de un cambio específico
     * 
     * @param int $id ID del cambio
     * @return array Detalles del cambio
     */
    public function getById(int $id): array
    {
        try {
            $response = $this->makeRequest('GET', "cambios-animal/{$id}");
            
            if ($response['success']) {
                return $response['data'] ?? [];
            }
            
            return [];
        } catch (Exception $e) {
            \Log::error('Error obteniendo cambio de animal: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene la lista de animales para selects
     * 
     * @return array Lista de animales
     */
    public function getAnimales(): array
    {
        try {
            $response = $this->makeRequest('GET', 'animales');
            
            if ($response['success']) {
                return $response['data'] ?? [];
            }
            
            return [];
        } catch (Exception $e) {
            \Log::error('Error obteniendo animales: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene la lista de fincas para filtros
     * 
     * @return array Lista de fincas
     */
    public function getFincas(): array
    {
        try {
            $response = $this->makeRequest('GET', 'fincas');
            
            if ($response['success']) {
                return $response['data'] ?? [];
            }
            
            return [];
        } catch (Exception $e) {
            \Log::error('Error obteniendo fincas: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene estadísticas de cambios 
     * 
     * @return array Estadísticas agregadas
     */
    public function getEstadisticas(): array
    {
        try {
            $cambios = $this->getList();
            
            $estadisticas = [
                'total_cambios' => count($cambios),
                'por_etapa' => [],
                'ultimos_30_dias' => 0,
                'promedio_peso' => 0,
                'promedio_altura' => 0
            ];
            
            if (empty($cambios)) {
                return $estadisticas;
            }
            
            // Agrupar por etapa
            $porEtapa = [];
            $pesos = [];
            $alturas = [];
            $fechaLimite = date('Y-m-d', strtotime('-30 days'));
            $recientes = 0;
            
            foreach ($cambios as $cambio) {
                $etapa = $cambio['Etapa_Cambio'];
                $porEtapa[$etapa] = ($porEtapa[$etapa] ?? 0) + 1;
                
                if ($cambio['Peso']) {
                    $pesos[] = $cambio['Peso'];
                }
                if ($cambio['Altura']) {
                    $alturas[] = $cambio['Altura'];
                }
                
                if ($cambio['Fecha_Cambio'] >= $fechaLimite) {
                    $recientes++;
                }
            }
            
            $estadisticas['por_etapa'] = $porEtapa;
            $estadisticas['ultimos_30_dias'] = $recientes;
            $estadisticas['promedio_peso'] = !empty($pesos) ? round(array_sum($pesos) / count($pesos), 1) : 0;
            $estadisticas['promedio_altura'] = !empty($alturas) ? round(array_sum($alturas) / count($alturas), 1) : 0;
            
            return $estadisticas;
        } catch (Exception $e) {
            \Log::error('Error calculando estadísticas de cambios: ' . $e->getMessage());
            return [
                'total_cambios' => 0,
                'por_etapa' => [],
                'ultimos_30_dias' => 0,
                'promedio_peso' => 0,
                'promedio_altura' => 0
            ];
        }
    }
}