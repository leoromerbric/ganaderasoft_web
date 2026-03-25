<?php

namespace App\Services\Contracts;

interface CambiosAnimalServiceInterface
{
    /**
     * Obtiene la lista de todos los cambios de animales
     * 
     * @param array $filtros Filtros opcionales para la consulta
     * @return array Lista de cambios de animales
     */
    public function getList(?int $idAnimal = null, ?int $idFinca = null): array;

    /**
     * Crea un nuevo registro de cambio de animal
     * 
     * @param array $data Datos del cambio de animal
     * @return array Respuesta de la API
     */
    public function create(array $data): array;

    /**
     * Obtiene los detalles de un cambio específico
     * 
     * @param int $id ID del cambio
     * @return array Detalles del cambio
     */
    public function getById(int $id): array;

    /**
     * Obtiene la lista de animales para los select
     * 
     * @return array Lista de animales
     */
    public function getAnimales(): array;

    /**
     * Obtiene la lista de fincas para los filtros
     * 
     * @return array Lista de fincas
     */
    public function getFincas(): array;

    /**
     * Obtiene estadísticas de cambios
     * 
     * @return array Estadísticas de cambios por tipo
     */
    public function getEstadisticas(): array;
}