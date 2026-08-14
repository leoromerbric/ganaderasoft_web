<?php

namespace App\Services\Api;

use App\Services\Contracts\ArbolGenServiceInterface;

class ApiArbolGenService extends BaseApiService implements ArbolGenServiceInterface
{
    /**
     * Obtiene el árbol genealógico completo de un animal (hasta 3 generaciones y descendencia).
     *
     * @param int $animalId Identificador único del animal.
     * @return array Respuesta estructurada con los datos del árbol genealógico.
     */
    public function getArbol(int $animalId): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'data' => []];
        }

        return $this->get("/animales/{$animalId}/arbol");
    }

    /**
     * Registra o actualiza la relación de un progenitor (Padre o Madre) para un animal.
     *
     * @param int $animalId Identificador único del animal hijo.
     * @param array $data Datos de la relación (tipo: 'Padre'|'Madre', padre_id: int).
     * @return array Respuesta estructurada indicando el resultado de la operación.
     */
    public function setProgenitor(int $animalId, array $data): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->post("/animales/{$animalId}/progenitor", $data);
    }

    /**
     * Elimina la relación de un progenitor (Padre o Madre) de un animal.
     *
     * @param int $animalId Identificador único del animal hijo.
     * @param string $tipo Tipo de progenitor a eliminar ('Padre' o 'Madre').
     * @return array Respuesta estructurada indicando el resultado de la eliminación.
     */
    public function removeProgenitor(int $animalId, string $tipo): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        return $this->delete("/animales/{$animalId}/progenitor/{$tipo}");
    }

    /**
     * Obtiene la lista de animales disponibles y aptos para ser asignados como progenitor.
     *
     * @param int $animalId Identificador único del animal hijo.
     * @param string $tipo Tipo de progenitor a consultar ('Padre' o 'Madre').
     * @return array Respuesta estructurada con la lista de candidatos disponibles.
     */
    public function getDisponibles(int $animalId, string $tipo): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'data' => []];
        }

        return $this->get("/animales/{$animalId}/progenitores-disponibles?tipo={$tipo}");
    }
}
