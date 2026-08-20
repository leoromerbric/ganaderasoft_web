<?php

namespace App\Services\Contracts;

interface VacunacionServiceInterface
{
    /**
     * Obtener listado de registros de vacunación con filtros.
     *
     * @param array $filters
     * @return array
     */
    public function getList(array $filters = []): array;

    /**
     * Obtener un registro de vacunación por su ID.
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array;

    /**
     * Registrar una o múltiples vacunaciones.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array;

    /**
     * Actualizar los datos de un registro de vacunación.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update(int $id, array $data): array;

    /**
     * Eliminar un registro de vacunación.
     *
     * @param int $id
     * @return array
     */
    public function eliminar(int $id): array;
}
