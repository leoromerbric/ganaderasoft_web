<?php

namespace App\Services\Contracts;

interface LactanciaServiceInterface
{
    public function getLactancias(?int $animalId = null, ?bool $activa = null, ?string $fechaInicio = null, ?string $fechaFin = null, bool $nopaginate = true): array;
    public function getLactancia(int $id): array;
    public function createLactancia(array $data): array;
    public function updateLactancia(int $id, array $data): array;
    public function deleteLactancia(int $id): array;
}