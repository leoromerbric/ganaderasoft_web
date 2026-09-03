<?php

namespace App\Services\Api;

use App\Services\Contracts\AnimalesServiceInterface;

class ApiAnimalesService extends BaseApiService implements AnimalesServiceInterface
{
    /**
     * Obtiene la lista de animales para el usuario autenticado.
     * Permite filtrar por rebaño, estado de archivado y solicita resultados sin paginar por defecto.
     *
     * @param int|null $rebanoId ID del rebaño para filtrar (opcional).
     * @param array $filters Filtros adicionales.
     * @return array Respuesta de la API.
     */
    public function getAnimales(?int $rebanoId = null, array $filters = []): array
    {
        $params = $filters;
        if ($rebanoId) {
            $params['rebano_id'] = $rebanoId;
        }

        return $this->get('/animales' . $this->buildQuery($params, true));
    }

    /**
     * Obtiene el detalle de un animal específico mediante su ID.
     *
     * @param int $id Identificador del animal.
     * @return array
     */
    public function getAnimal(int $id): array
    {
        return $this->get("/animales/{$id}");
    }

    /**
     * Crea un nuevo registro de animal.
     *
     * @param array $data Datos del animal a crear.
     * @return array
     */
    public function createAnimal(array $data): array
    {
        return $this->post('/animales', $data);
    }

    /**
     * Actualiza la información de un animal existente.
     *
     * @param int $id Identificador del animal a actualizar.
     * @param array $data Nuevos datos para el animal.
     * @return array
     */
    public function updateAnimal(int $id, array $data): array
    {
        return $this->put("/animales/{$id}", $data);
    }

    /**
     * Archiva un animal activo.
     *
     * @param int $id
     * @return array
     */
    public function archiveAnimal(int $id): array
    {
        return $this->post("/animales/{$id}/archivar");
    }

    /**
     * Desarchiva un animal archivado.
     *
     * @param int $id
     * @return array
     */
    public function unarchiveAnimal(int $id): array
    {
        return $this->post("/animales/{$id}/desarchivar");
    }

    /**
     * Elimina definitivamente un animal del sistema.
     *
     * @param int $id
     * @return array
     */
    public function deleteAnimal(int $id): array
    {
        return $this->delete("/animales/{$id}");
    }

    /**
     * Obtiene el catálogo de composiciones de razas disponibles.
     *
     * @return array
     */
    public function getRazas(): array
    {
        return $this->get('/composicion-raza' . $this->buildQuery([], true));
    }

    /**
     * Obtiene el catálogo de estados de salud disponibles.
     *
     * @return array
     */
    public function getEstadosSalud(): array
    {
        return $this->get('/estados-salud' . $this->buildQuery([], true));
    }

    /**
     * Obtiene el catálogo de etapas de crecimiento/producción disponibles.
     *
     * @return array
     */
    public function getEtapas(): array
    {
        return $this->get('/etapas' . $this->buildQuery([], true));
    }

    /**
     * Importa masivamente animales desde un archivo CSV o TXT.
     *
     * @param int $fincaId ID de la finca destino.
     * @param mixed $file Archivo UploadedFile.
     * @return array
     */
    public function importarAnimales(int $fincaId, $file): array
    {
        return $this->postMultipart(
            '/animales/importar',
            ['finca_id' => $fincaId],
            ['archivo' => $file]
        );
    }
}
