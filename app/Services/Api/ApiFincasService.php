<?php

namespace App\Services\Api;

use App\Services\Contracts\FincasServiceInterface;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class ApiFincasService extends BaseApiService implements FincasServiceInterface
{
    /**
     * Obtiene la lista de fincas del usuario autenticado.
     *
     * @param array $params
     * @return array
     */
    public function getFincas(array $params = []): array
    {
        return $this->get('/fincas' . $this->buildQuery($params, true));
    }

    /**
     * Obtiene los datos de una finca específica por su ID.
     *
     * @param int $id
     * @return array
     */
    public function getFinca(int $id): array
    {
        return $this->get("/fincas/{$id}");
    }

    /**
     * Crea un nuevo registro de finca.
     *
     * @param array $data
     * @return array
     */
    public function createFinca(array $data): array
    {
        return $this->post('/fincas', $data);
    }

    /**
     * Actualiza la información de una finca existente.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateFinca(int $id, array $data): array
    {
        return $this->put("/fincas/{$id}", $data);
    }

    /**
     * Archiva una finca activa.
     *
     * @param int $id
     * @return array
     */
    public function archiveFinca(int $id): array
    {
        return $this->post("/fincas/{$id}/archivar");
    }

    /**
     * Desarchiva una finca archivada.
     *
     * @param int $id
     * @return array
     */
    public function unarchiveFinca(int $id): array
    {
        return $this->post("/fincas/{$id}/desarchivar");
    }

    /**
     * Elimina definitivamente una finca y sus registros dependientes en cascada.
     *
     * @param int $id
     * @return array
     */
    public function deleteFinca(int $id): array
    {
        return $this->delete("/fincas/{$id}");
    }

    /**
     * Importa masivamente fincas desde un archivo CSV o TXT.
     *
     * @param UploadedFile $file
     * @param int|null $propietarioId
     * @return array
     */
    public function importarFincas(UploadedFile $file, ?int $propietarioId = null): array
    {
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
     * @return Response
     */
    public function descargarPlantilla(): Response
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
