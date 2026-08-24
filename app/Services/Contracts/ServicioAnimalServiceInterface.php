<?php

namespace App\Services\Contracts;

interface ServicioAnimalServiceInterface
{
    public function getList(?int $animalId = null, ?string $tipo = null, ?string $fechaInicio = null, ?string $fechaFin = null, ?int $fincaId = null, ?int $rebanoId = null): array;
    public function getById(int $id): array;
    public function create(array $data): array;
    public function update(int $id, array $data): array;
    public function eliminar(int $id): array;
    public function getAnimales(): array;
    public function getSemenToros(): array;
    public function getPersonalFinca(): array;
    public function getRegistrosCelo(): array;
}
