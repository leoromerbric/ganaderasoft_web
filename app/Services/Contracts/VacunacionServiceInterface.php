<?php

namespace App\Services\Contracts;

interface VacunacionServiceInterface
{
    public function getList(array $filters = []): array;
    public function getById(int $id): array;
    public function create(array $data): array;
    public function update(int $id, array $data): array;
    public function eliminar(int $id): array;
    public function getAnimalesElegibles(array $filters): array;
    public function getVacunas(): array;
    public function getEtapas(): array;
    public function getRebanos(): array;
}
