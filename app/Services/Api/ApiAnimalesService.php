<?php

namespace App\Services\Api;

use App\Services\Contracts\AnimalesServiceInterface;

class ApiAnimalesService extends BaseApiService implements AnimalesServiceInterface
{
    /**
     * Obtiene la lista de animales para el usuario autenticado.
     * Permite filtrar por rebaño, estado de archivado y solicita resultados sin paginar.
     *
     * @param int|null $rebanoId ID del rebaño para filtrar (opcional).
     * @param array $filters Filtros adicionales (ej: 'archivado' => true/'todos').
     * @return array Respuesta de la API con el listado de animales.
     */
    public function getAnimales(?int $rebanoId = null, array $filters = []): array
    {
        $params = array_merge(['nopaginate' => 'true'], $filters);
        
        if ($rebanoId) {
            $params['rebano_id'] = $rebanoId;
        }

        return $this->get('/animales?' . http_build_query($params));
    }

    /**
     * Obtiene el detalle de un animal específico mediante su ID.
     *
     * @param int $id Identificador del animal.
     * @return array Respuesta de la API con los datos del animal.
     */
    public function getAnimal(int $id): array
    {
        return $this->get("/animales/{$id}");
    }

    /**
     * Crea un nuevo registro de animal.
     *
     * @param array $data Datos del animal a crear.
     * @return array Respuesta de la API indicando el resultado de la creación.
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
     * @return array Respuesta de la API indicando el resultado de la actualización.
     */
    public function updateAnimal(int $id, array $data): array
    {
        return $this->put("/animales/{$id}", $data);
    }

    /**
     * Restaura un animal archivado.
     *
     * @param int $id Identificador del animal a restaurar.
     * @return array Respuesta de la API.
     */
    public function restoreAnimal(int $id): array
    {
        return $this->post("/animales/{$id}/restaurar");
    }

    /**
     * Obtiene el catálogo de composiciones de razas disponibles.
     *
     * @return array Respuesta de la API con el listado de razas.
     */
    public function getRazas(): array
    {
        return $this->get('/composicion-raza');
    }

    /**
     * Obtiene el catálogo de estados de salud disponibles.
     *
     * @return array Respuesta de la API con el listado de estados de salud.
     */
    public function getEstadosSalud(): array
    {
        return $this->get('/estados-salud');
    }

    /**
     * Obtiene el catálogo de etapas de crecimiento/producción disponibles.
     *
     * @return array Respuesta de la API con el listado de etapas.
     */
    public function getEtapas(): array
    {
        return $this->get('/etapas');
    }

    /**
     * Importa masivamente animales desde un archivo CSV o TXT.
     *
     * @param int $fincaId ID de la finca destino.
     * @param mixed $file Archivo UploadedFile.
     * @return array Respuesta de la API.
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
