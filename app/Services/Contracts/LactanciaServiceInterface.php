<?php

namespace App\Services\Contracts;

interface LactanciaServiceInterface
{
    /**
     * Get list of lactation periods
     */
    public function getLactancias(?int $animalId = null, ?bool $activa = null, ?string $fechaInicio = null, ?string $fechaFin = null): array;

    /**
     * Get a single lactation period by ID
     */
    public function getLactancia(int $id): array;

    /**
     * Create a new lactation period
     */
    public function createLactancia(array $data): array;

    /**
     * Update an existing lactation period
     */
    public function updateLactancia(int $id, array $data): array;

    /**
     * Delete a lactation period
     */
    public function deleteLactancia(int $id): array;
}