<?php

namespace App\Services\Api;

use App\Services\Contracts\ArbolGenServiceInterface;

class ApiArbolGenService extends BaseApiService implements ArbolGenServiceInterface
{
    /**
     * Obtiene el árbol genealógico completo de un animal (hasta 3 generaciones y descendencia).
     *
     * @param int $animalId Identificador único del animal.
     * @return array
     */
    public function getArbol(int $animalId): array
    {
        return $this->get("/animales/{$animalId}/arbol");
    }

    /**
     * Registra o actualiza la relación de un progenitor (Padre o Madre) para un animal.
     *
     * @param int $animalId Identificador único del animal hijo.
     * @param array $data Datos de la relación (tipo: 'Padre'|'Madre', padre_id: int).
     * @return array
     */
    public function setProgenitor(int $animalId, array $data): array
    {
        return $this->post("/animales/{$animalId}/progenitor", $data);
    }

    /**
     * Elimina la relación de un progenitor (Padre o Madre) de un animal.
     *
     * @param int $animalId Identificador único del animal hijo.
     * @param string $tipo Tipo de progenitor a eliminar ('Padre' o 'Madre').
     * @return array
     */
    public function removeProgenitor(int $animalId, string $tipo): array
    {
        return $this->delete("/animales/{$animalId}/progenitor/{$tipo}");
    }

    /**
     * Obtiene la lista de animales disponibles y aptos para ser asignados como progenitor.
     *
     * @param int $animalId Identificador único del animal hijo.
     * @param string $tipo Tipo de progenitor a consultar ('Padre' o 'Madre').
     * @return array
     */
    public function getDisponibles(int $animalId, string $tipo): array
    {
        return $this->get("/animales/{$animalId}/progenitores-disponibles" . $this->buildQuery(['tipo' => $tipo]));
    }
}
