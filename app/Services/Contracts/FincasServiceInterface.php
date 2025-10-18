<?php

namespace App\Services\Contracts;

interface FincasServiceInterface
{
    /**
     * Get list of fincas for authenticated user
     */
    public function getFincas(): array;
}
