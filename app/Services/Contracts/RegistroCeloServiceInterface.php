<?php

namespace App\Services\Contracts;

interface RegistroCeloServiceInterface
{
    public function getList(?int $animalId = null, ?string $fechaInicio = null, ?string $fechaFin = null): array;
    public function getById(int $id): array;
    public function create(array $data): array;
    public function update(int $id, array $data): array;
    public function eliminar(int $id): array;
    public function getAnimales(): array;
}
