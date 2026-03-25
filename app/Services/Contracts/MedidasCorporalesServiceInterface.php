<?php

namespace App\Services\Contracts;

interface MedidasCorporalesServiceInterface
{
    /**
     * Get list of body measurements
     */
    public function getMedidasCorporales(?int $animalId = null, ?int $etapaId = null): array;

    /**
     * Get a single body measurement record by ID
     */
    public function getMedidaCorporal(int $id): array;

    /**
     * Create a new body measurement record
     */
    public function createMedidaCorporal(array $data): array;

    /**
     * Update an existing body measurement record
     */
    public function updateMedidaCorporal(int $id, array $data): array;

    /**
     * Delete a body measurement record
     */
    public function deleteMedidaCorporal(int $id): array;
}