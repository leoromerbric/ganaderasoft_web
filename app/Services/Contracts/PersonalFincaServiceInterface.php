<?php

namespace App\Services\Contracts;

interface PersonalFincaServiceInterface
{
    /**
     * Get list of personal de finca
     */
    public function getPersonalFinca(?int $fincaId = null): array;

    /**
     * Get a single personal de finca record by ID
     */
    public function getPersonalFincaById(int $id): array;

    /**
     * Create a new personal de finca record
     */
    public function createPersonalFinca(array $data): array;

    /**
     * Update an existing personal de finca record
     */
    public function updatePersonalFinca(int $id, array $data): array;

    /**
     * Delete a personal de finca record
     */
    public function deletePersonalFinca(int $id): array;
}