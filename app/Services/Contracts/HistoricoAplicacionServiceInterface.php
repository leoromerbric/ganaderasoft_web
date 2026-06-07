<?php

namespace App\Services\Contracts;

interface HistoricoAplicacionServiceInterface
{
    public function getList(?int $vacunaId = null, ?string $fechaInicio = null, ?string $fechaFin = null): array;
    public function getById(int $id): array;
    public function create(array $data): array;
    public function update(int $id, array $data): array;
    public function eliminar(int $id): array;
    public function getVacunas(): array;
    public function getCasasComerciales(): array;
    public function getDosis(): array;
}
