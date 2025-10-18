<?php

namespace App\Services\Contracts;

interface AnimalesServiceInterface
{
    /**
     * Get list of animals for authenticated user
     */
    public function getAnimales(?int $rebanoId = null): array;

    /**
     * Get a single animal by ID
     */
    public function getAnimal(int $id): array;

    /**
     * Create a new animal
     */
    public function createAnimal(array $data): array;

    /**
     * Update an existing animal
     */
    public function updateAnimal(int $id, array $data): array;

    /**
     * Get list of available breeds (composicion_raza)
     */
    public function getRazas(): array;

    /**
     * Get list of available health states
     */
    public function getEstadosSalud(): array;

    /**
     * Get list of available animal stages
     */
    public function getEtapas(): array;
}
