<?php

namespace App\Services\Contracts;

interface PersonalServiceInterface
{
    /**
     * Get list of personal for a given finca
     */
    public function getPersonal(int $idFinca): array;

    /**
     * Create new personal for a finca
     */
    public function createPersonal(array $data): array;

    /**
     * Update existing personal
     */
    public function updatePersonal(int $id, array $data): array;
}
