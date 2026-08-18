<?php

namespace App\Services\Contracts;

interface VacunaServiceInterface
{
    public function getAll(array $params = []): array;
    public function getById(int $id): array;
    public function create(array $data): array;
    public function update(int $id, array $data): array;
    public function deleteItem(int $id): array;
}
