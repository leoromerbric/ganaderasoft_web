<?php

namespace App\Services\Contracts;

interface LecheServiceInterface
{
    /**
     * Get list of milk production records
     */
    public function getRegistrosLeche(?int $lactanciaId = null, ?string $fechaInicio = null, ?string $fechaFin = null): array;

    /**
     * Get a single milk production record by ID
     */
    public function getRegistroLeche(int $id): array;

    /**
     * Create a new milk production record
     */
    public function createRegistroLeche(array $data): array;

    /**
     * Update an existing milk production record
     */
    public function updateRegistroLeche(int $id, array $data): array;

    /**
     * Delete a milk production record
     */
    public function deleteRegistroLeche(int $id): array;
}