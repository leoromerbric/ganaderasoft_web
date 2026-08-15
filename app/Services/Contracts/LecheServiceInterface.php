<?php

namespace App\Services\Contracts;

interface LecheServiceInterface
{
    public function getRegistrosLeche(?int $lactanciaId = null, ?string $fechaInicio = null, ?string $fechaFin = null, bool $nopaginate = true): array;
    public function getRegistroLeche(int $id): array;
    public function createRegistroLeche(array $data): array;
    public function updateRegistroLeche(int $id, array $data): array;
    public function deleteRegistroLeche(int $id): array;
}