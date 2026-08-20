<?php

namespace App\Services\Contracts;

interface MedidasCorporalesServiceInterface
{
    public function getMedidasCorporales(?int $animalId = null, ?int $etapaId = null, bool $nopaginate = true): array;
    public function getMedidaCorporal(int $id): array;
    public function createMedidaCorporal(array $data): array;
    public function updateMedidaCorporal(int $id, array $data): array;
    public function deleteMedidaCorporal(int $id): array;
    public function getIndicesByMedida(int $id): array;
    public function getEvolucionIndices(int $animalId): array;
}