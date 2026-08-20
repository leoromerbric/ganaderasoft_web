<?php

namespace App\Services\Api;

use App\Services\Contracts\FincasServiceInterface;

class ApiFincasService extends BaseApiService implements FincasServiceInterface
{
    /**
     * Versión de API V2 para el módulo de Fincas (Patrón Estrangulador)
     */
    protected string $apiVersion = '2';

    /**
     * Get list of fincas for authenticated user
     */
    public function getFincas(): array
    {
        return $this->get('/fincas');
    }

    /**
     * Obtiene los datos de una finca en específico por su ID.
     *
     * @param int $id Identificador único de la finca.
     * @return array Respuesta de la API con los datos de la finca.
     */
    public function getFinca(int $id): array
    {
        return $this->get("/fincas/{$id}");
    }

    /**
     * Crea un nuevo registro de finca.
     *
     * @param array $data Datos de la finca a crear.
     * @return array Respuesta de la API indicando el resultado de la creación.
     */
    public function createFinca(array $data): array
    {
        return $this->post('/fincas', $data);
    }

    /**
     * Actualiza la información de una finca existente.
     *
     * @param int $id Identificador único de la finca a actualizar.
     * @param array $data Datos actualizados de la finca.
     * @return array Respuesta de la API indicando el resultado de la actualización.
     */
    public function updateFinca(int $id, array $data): array
    {
        return $this->put("/fincas/{$id}", $data);
    }

    /**
     * Importa masivamente fincas desde un archivo CSV o TXT.
     *
     * @param \Illuminate\Http\UploadedFile $file Archivo a importar.
     * @param int|null $propietarioId ID del propietario asociado (opcional).
     * @return array Respuesta de la API.
     */
    public function importarFincas(\Illuminate\Http\UploadedFile $file, ?int $propietarioId = null): array
    {
        if (!session('user.token')) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        $data = [];
        if ($propietarioId) {
            $data['propietario_id'] = $propietarioId;
        }

        return $this->postMultipart(
            '/fincas/importar',
            $data,
            ['archivo' => $file]
        );
    }

    /**
     * Descarga la plantilla CSV oficial de ejemplo para importación masiva.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function descargarPlantilla(): \Symfony\Component\HttpFoundation\Response
    {
        $csvContent = "nombre,explotacion_tipo,identificador_hierro,superficie,relieve,fuente_agua\n"
                    . "Hacienda Santa Ines,Mixto,HSI-01,150.5,Plano,Rio\n"
                    . "Finca El Porvenir,Intensiva,FEP-02,85.0,Ondulado,Pozo\n"
                    . "Agropecuaria San Jose,Extensiva,,220.0,Plano,Quebrada\n";

        return response($csvContent, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="plantilla_fincas.csv"',
        ]);
    }
}
