<?php

namespace App\Services\Contracts;

interface TratamientoServiceInterface
{
    public function getList(?int $diagnosticoId = null, ?string $fechaInicio = null, ?string $fechaFin = null): array;
    public function getById(int $id): array;
    public function create(array $data): array;
    public function update(int $id, array $data): array;
    public function eliminar(int $id): array;
    public function getDiagnosticos(): array;
}
