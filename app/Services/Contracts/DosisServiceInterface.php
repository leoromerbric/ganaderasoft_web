<?php

namespace App\Services\Contracts;

interface DosisServiceInterface
{
    public function getList(?int $vacunaId = null, ?bool $vigentes = null): array;
    public function getById(int $id): array;
    public function create(array $data): array;
    public function update(int $id, array $data): array;
    public function eliminar(int $id): array;
    public function getVacunas(): array;
    public function getCasasComerciales(): array;
    public function getAnimales(): array;
}