<?php

namespace App\Services\Contracts;

interface VacunaServiceInterface
{
    public function getList(?string $nombre = null): array;
    public function getById(int $id): array;
    public function create(array $data): array;
    public function update(int $id, array $data): array;
    public function eliminar(int $id): array;
}
