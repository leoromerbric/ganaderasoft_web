<?php

namespace App\Services\Contracts;

interface FincasServiceInterface
{
    /**
     * Get list of fincas for authenticated user
     */
    public function getFincas(): array;

    /**
     * Get a single finca by ID
     */
    public function getFinca(int $id): array;

    /**
     * Create a new finca
     */
    public function createFinca(array $data): array;

    /**
     * Update an existing finca
     */
    public function updateFinca(int $id, array $data): array;
}
