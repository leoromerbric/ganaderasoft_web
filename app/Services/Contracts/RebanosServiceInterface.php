<?php

namespace App\Services\Contracts;

interface RebanosServiceInterface
{
    /**
     * Get list of rebaños for authenticated user
     */
    public function getRebanos(): array;

    /**
     * Create a new rebaño
     */
    public function createRebano(array $data): array;

    /**
     * Update an existing rebaño
     */
    public function updateRebano(int $id, array $data): array;
}
