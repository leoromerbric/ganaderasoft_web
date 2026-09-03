<?php

namespace App\Services\Contracts;

interface FincasServiceInterface
{
    /**
     * Get list of fincas for authenticated user
     */
    public function getFincas(array $params = []): array;

    /**
     * Get a single finca by ID
     */
    public function getFinca(int $id): array;

    /**
     * Create a new finca
     */
    public function createFinca(array $data): array;

    /**
     * Update an existing finca
     */
    public function updateFinca(int $id, array $data): array;

    /**
     * Importar fincas masivamente a partir de un archivo CSV o TXT.
     */
    public function importarFincas(\Illuminate\Http\UploadedFile $file, ?int $propietarioId = null): array;

    /**
     * Archivar una finca.
     */
    public function archiveFinca(int $id): array;

    /**
     * Desarchivar una finca previamente archivada.
     */
    public function unarchiveFinca(int $id): array;

    /**
     * Eliminar definitivamente una finca y sus dependencias en cascada.
     */
    public function deleteFinca(int $id): array;

    /**
     * Descargar plantilla de ejemplo para importación masiva de fincas.
     */
    public function descargarPlantilla(): \Symfony\Component\HttpFoundation\Response;
}

