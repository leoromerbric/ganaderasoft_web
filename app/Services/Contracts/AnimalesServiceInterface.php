<?php

namespace App\Services\Contracts;

interface AnimalesServiceInterface
{
    /**
     * Get list of animals for authenticated user
     */
    public function getAnimales(?int $rebanoId = null, array $filters = []): array;

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
     * Archive an active animal
     */
    public function archiveAnimal(int $id): array;

    /**
     * Unarchive an archived animal
     */
    public function unarchiveAnimal(int $id): array;

    /**
     * Permanently delete an animal
     */
    public function deleteAnimal(int $id): array;


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

    /**
     * Import animals in bulk from a CSV or TXT file
     */
    public function importarAnimales(int $fincaId, $file): array;
}
