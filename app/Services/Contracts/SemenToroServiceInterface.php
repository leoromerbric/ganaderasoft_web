<?php

namespace App\Services\Contracts;

interface SemenToroServiceInterface
{
    public function getList(?int $toroId = null, ?bool $activo = null): array;
    public function getById(int $id): array;
    public function create(array $data): array;
    public function update(int $id, array $data): array;
    public function eliminar(int $id): array;
    public function getToros(): array;
}
