<?php

namespace App\Services\Contracts;

interface ArbolGenServiceInterface
{
    /** Devuelve el árbol genealógico completo (3 generaciones + hijos). */
    public function getArbol(int $animalId): array;

    /** Registra o actualiza la relación Padre o Madre de un animal. */
    public function setProgenitor(int $animalId, array $data): array;

    /** Elimina la relación Padre o Madre de un animal. */
    public function removeProgenitor(int $animalId, string $tipo): array;

    /** Lista animales disponibles para asignar como progenitor. */
    public function getDisponibles(int $animalId, string $tipo): array;
}
