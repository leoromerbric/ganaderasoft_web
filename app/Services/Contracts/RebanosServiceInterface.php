<?php

namespace App\Services\Contracts;

interface RebanosServiceInterface
{
    /**
     * Get list of rebaños for authenticated user
     */
    public function getRebanos(): array;
}
