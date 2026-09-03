<?php

namespace App\Services\Contracts;

interface RebanosServiceInterface
{
    /**
     * Get list of rebaños for authenticated user
     */
    public function getRebanos(array $params = []): array;

    /**
     * Get a single rebaño by ID
     */
    public function getRebano(int $id): array;

    /**
     * Create a new rebaño
     */
    public function createRebano(array $data): array;

    /**
     * Update an existing rebaño
     */
    public function updateRebano(int $id, array $data): array;

    /**
     * Archivar un rebaño activo.
     */
    public function archiveRebano(int $id): array;

    /**
     * Desarchivar un rebaño archivado.
     */
    public function unarchiveRebano(int $id): array;

    /**
     * Eliminar definitivamente un rebaño y sus animales en cascada.
     */
    public function deleteRebano(int $id): array;
}

