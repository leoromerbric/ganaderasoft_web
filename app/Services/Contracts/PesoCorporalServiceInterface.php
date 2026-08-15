<?php

namespace App\Services\Contracts;

interface PesoCorporalServiceInterface
{
    public function getPesosCorporales(?int $animalId = null, ?string $fechaInicio = null, ?string $fechaFin = null, bool $nopaginate = true): array;
    public function getPesoCorporal(int $id): array;
    public function createPesoCorporal(array $data): array;
    public function updatePesoCorporal(int $id, array $data): array;
    public function deletePesoCorporal(int $id): array;
}