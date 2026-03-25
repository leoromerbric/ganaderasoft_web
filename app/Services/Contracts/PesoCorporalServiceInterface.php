<?php

namespace App\Services\Contracts;

interface PesoCorporalServiceInterface
{
    /**
     * Get list of weight records
     */
    public function getPesosCorporales(?int $animalId = null, ?string $fechaInicio = null, ?string $fechaFin = null): array;

    /**
     * Get a single weight record by ID
     */
    public function getPesoCorporal(int $id): array;

    /**
     * Create a new weight record
     */
    public function createPesoCorporal(array $data): array;

    /**
     * Update an existing weight record
     */
    public function updatePesoCorporal(int $id, array $data): array;

    /**
     * Delete a weight record
     */
    public function deletePesoCorporal(int $id): array;
}