<?php

namespace App\Services\Contracts;

interface PersonalServiceInterface
{
    /**
     * Get list of personal for a given finca
     */
    public function getPersonal(int $idFinca): array;
}
