<?php

namespace App\Services\Contracts;

interface MovimientoRebanoServiceInterface
{
    public function getList(?int $fincaId = null, ?int $rebanoId = null, bool $nopaginate = true): array;
    public function getById(int $id): array;
    public function create(array $data): array;
    public function update(int $id, array $data): array;
    public function eliminar(int $id): array;
    public function getFincas(): array;
    public function getRebanos(): array;
    public function getAnimales(): array;
}
