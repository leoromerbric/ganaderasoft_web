<?php

namespace App\Http\Controllers;

use App\Services\Contracts\AnimalesServiceInterface;
use App\Services\Contracts\FincasServiceInterface;
use App\Services\Contracts\MedidasCorporalesServiceInterface;
use App\Services\Contracts\RebanosServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Controlador para el Control y Registro de Medidas Corporales (Morfometría).
 * 
 * Administra las mediciones físicas e interactúa exclusivamente
 * con la API v2.
 */
class MedidasCorporalesController extends Controller
{
    public function __construct(
        protected MedidasCorporalesServiceInterface $medidasCorporalesService,
        protected AnimalesServiceInterface $animalesService,
        protected FincasServiceInterface $fincasService,
        protected RebanosServiceInterface $rebanosService
    ) {}

    /**
     * Extrae un mensaje legible de error desde la respuesta de la API v2.
     *
     * @param array<string, mixed> $response
     * @param string $fallback
     * @return string
     */
    private function apiMessage(array $response, string $fallback): string
    {
        if (!empty($response['message']) && is_string($response['message'])) {
            return $response['message'];
        }

        if (!empty($response['errors']) && is_array($response['errors'])) {
            $first = collect($response['errors'])->flatten()->first();
            if (is_string($first) && $first !== '') {
                return $first;
            }
        }

        return $fallback;
    }

    /**
     * Muestra la lista de registros de medidas corporales con estadísticas y filtros.
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function index(Request $request): View|RedirectResponse
    {
        try {
            $animalId = $request->query('animal_id') ? (int) $request->query('animal_id') : null;

            $response = $this->medidasCorporalesService->getMedidasCorporales(null);

            if (!($response['success'] ?? false)) {
                return redirect()->route('dashboard')->with('error', $this->apiMessage($response, 'Error al consultar medidas corporales.'));
            }

            // Cargar animales para los selectores de filtro y mapeo histórico
            $animalesResponse = $this->animalesService->getAnimales(null, ['incluir_archivados' => true]);
            $rawAnimales      = $animalesResponse['data'] ?? [];
            $animales         = is_array($rawAnimales)
                ? (isset($rawAnimales['data']) && is_array($rawAnimales['data']) ? $rawAnimales['data'] : array_values(array_filter($rawAnimales, 'is_array')))
                : [];

            $animalesPorId = collect($animales)
                ->filter(fn ($animal) => isset($animal['id']))
                ->keyBy(fn ($animal) => (int) $animal['id']);

            $rawMedidas = $response['data'] ?? [];
            $medidasCorporales = collect(is_array($rawMedidas) ? array_values(array_filter($rawMedidas, 'is_array')) : [])
                ->map(function ($medida) use ($animalesPorId) {
                    $anId = $medida['animal_id'] ?? data_get($medida, 'etapa_animal.animal_id') ?? data_get($medida, 'animal.id') ?? null;
                    $animal = $anId ? $animalesPorId->get((int) $anId, []) : [];

                    $rebanoId = data_get($medida, 'animal.rebano_id') ?? ($animal['rebano_id'] ?? data_get($animal, 'rebano.id'));
                    $fincaId  = data_get($medida, 'animal.rebano.finca_id') ?? data_get($animal, 'rebano.finca_id') ?? data_get($animal, 'rebano.finca.id');

                    $medida['animal_nombre'] = data_get($medida, 'animal.nombre')
                        ?? ($animal['nombre'] ?? ($anId ? ('Animal #' . $anId) : 'Animal no disponible'));
                    $medida['animal_identificacion'] = data_get($medida, 'animal.codigo_animal')
                        ?? ($animal['codigo_animal'] ?? '');
                    $medida['animal_id_ref'] = $anId;
                    $medida['rebano_id'] = $rebanoId;
                    $medida['finca_id'] = $fincaId;

                    return $medida;
                })->all();

            $alturas     = collect($medidasCorporales)->pluck('altura_hc')->filter(fn ($v) => is_numeric($v) && $v > 0)->map(fn ($v) => (float) $v);
            $longitudes  = collect($medidasCorporales)->pluck('longitud_lc')->filter(fn ($v) => is_numeric($v) && $v > 0)->map(fn ($v) => (float) $v);
            $perimetros  = collect($medidasCorporales)->pluck('perimetro_pt')->filter(fn ($v) => is_numeric($v) && $v > 0)->map(fn ($v) => (float) $v);

            $estadisticas = [
                'altura_promedio'         => $alturas->isNotEmpty() ? number_format($alturas->avg(), 1, '.', '') : '0.0',
                'largura_promedio'        => $longitudes->isNotEmpty() ? number_format($longitudes->avg(), 1, '.', '') : '0.0',
                'circunferencia_promedio' => $perimetros->isNotEmpty() ? number_format($perimetros->avg(), 1, '.', '') : '0.0',
            ];

            $fincasRes = $this->fincasService->getFincas(['incluir_archivados' => true]);
            $fincas = ($fincasRes['success'] ?? false) ? ($fincasRes['data']['data'] ?? $fincasRes['data'] ?? []) : [];

            $rebanosRes = $this->rebanosService->getRebanos(['incluir_archivados' => true]);
            $rebanos = ($rebanosRes['success'] ?? false) ? ($rebanosRes['data']['data'] ?? $rebanosRes['data'] ?? []) : [];

            $fincaId  = $request->query('finca_id') ? (int) $request->query('finca_id') : null;
            $rebanoId = $request->query('rebano_id') ? (int) $request->query('rebano_id') : null;

            if ($animalId && $animalesPorId->has($animalId)) {
                $an = $animalesPorId->get($animalId);
                if (!$fincaId) {
                    $fincaId = (int) (data_get($an, 'rebano.finca_id') ?? data_get($an, 'rebano.finca.id') ?? 0) ?: null;
                }
                if (!$rebanoId) {
                    $rebanoId = (int) ($an['rebano_id'] ?? data_get($an, 'rebano.id') ?? 0) ?: null;
                }
            } elseif ($rebanoId && !$fincaId) {
                $rebObj = collect($rebanos)->firstWhere('id', $rebanoId);
                if ($rebObj) {
                    $fincaId = $rebObj['finca_id'] ?? data_get($rebObj, 'finca.id') ?? null;
                }
            }

            return view('medidas-corporales.index', compact('medidasCorporales', 'animales', 'fincas', 'rebanos', 'animalId', 'fincaId', 'rebanoId', 'estadisticas'));
        } catch (\Exception $e) {
            Log::error('Error en MedidasCorporalesController@index: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error al cargar los registros de medidas corporales.');
        }
    }

    /**
     * Muestra el formulario para registrar nuevas medidas corporales.
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        $animalId = $request->query('animal_id') ? (int) $request->query('animal_id') : null;

        $animalesResponse = $this->animalesService->getAnimales();
        $rawAnimales      = $animalesResponse['data'] ?? [];
        $animales         = is_array($rawAnimales)
            ? (isset($rawAnimales['data']) && is_array($rawAnimales['data']) ? $rawAnimales['data'] : array_values(array_filter($rawAnimales, 'is_array')))
            : [];

        return view('medidas-corporales.create', compact('animales', 'animalId'));
    }

    /**
     * Almacena un nuevo registro de medidas corporales.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'animal_id'     => 'required|integer|min:1',
            'etapa_id'      => 'nullable|integer',
            'animal_etapa_id' => 'nullable|integer',
            'altura_hc'     => 'nullable|numeric|min:0|max:300',
            'altura_hg'     => 'nullable|numeric|min:0|max:300',
            'perimetro_pt'  => 'nullable|numeric|min:0|max:500',
            'perimetro_pca' => 'nullable|numeric|min:0|max:200',
            'longitud_lc'   => 'nullable|numeric|min:0|max:500',
            'longitud_lg'   => 'nullable|numeric|min:0|max:200',
            'anchura_ag'    => 'nullable|numeric|min:0|max:200',
        ], [
            'animal_id.required'  => 'El animal es requerido.',
            'animal_id.integer'   => 'El animal seleccionado no es válido.',
            'altura_hc.numeric'   => 'La altura a la cruz debe ser un número.',
            'altura_hc.max'       => 'La altura a la cruz no puede exceder 300 cm.',
            'altura_hg.numeric'   => 'La altura a la grupa debe ser un número.',
            'altura_hg.max'       => 'La altura a la grupa no puede exceder 300 cm.',
            'perimetro_pt.numeric'=> 'El perímetro torácico debe ser un número.',
            'perimetro_pt.max'    => 'El perímetro torácico no puede exceder 500 cm.',
            'perimetro_pca.numeric' => 'El perímetro de caña debe ser un número.',
            'perimetro_pca.max'   => 'El perímetro de caña no puede exceder 200 cm.',
            'longitud_lc.numeric' => 'La longitud corporal debe ser un número.',
            'longitud_lc.max'     => 'La longitud corporal no puede exceder 500 cm.',
            'longitud_lg.numeric' => 'La longitud de grupa debe ser un número.',
            'longitud_lg.max'     => 'La longitud de grupa no puede exceder 200 cm.',
            'anchura_ag.numeric'  => 'La anchura de grupa debe ser un número.',
            'anchura_ag.max'      => 'La anchura de grupa no puede exceder 200 cm.',
        ]);

        try {
            $response = $this->medidasCorporalesService->createMedidaCorporal($validatedData);

            if ($response['success'] ?? false) {
                return redirect()->route('medidas-corporales.index')
                    ->with('success', 'Medidas corporales registradas exitosamente.');
            }

            return back()->withInput()->with('error', $this->apiMessage($response, 'No se pudo registrar la medida corporal.'));
        } catch (\Exception $e) {
            Log::error('Error en MedidasCorporalesController@store: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error inesperado al guardar el registro de medidas.');
        }
    }

    /**
     * Muestra la vista detallada de una medida corporal.
     *
     * @param int $id ID del registro
     * @return View|RedirectResponse
     */
    public function show(int $id): View|RedirectResponse
    {
        try {
            $response = $this->medidasCorporalesService->getMedidaCorporal($id);

            if (!($response['success'] ?? false) || empty($response['data'])) {
                return redirect()->route('medidas-corporales.index')->with('error', $this->apiMessage($response, 'Registro de medida corporal no encontrado.'));
            }

            $medidaCorporal = $response['data'];

            // Consultar análisis e índices zoométricos calculados on-the-fly
            $indicesResponse = $this->medidasCorporalesService->getIndicesByMedida($id);
            $indicesData     = ($indicesResponse['success'] ?? false) ? ($indicesResponse['data'] ?? null) : null;

            return view('medidas-corporales.show', compact('medidaCorporal', 'indicesData'));
        } catch (\Exception $e) {
            Log::error("Error en MedidasCorporalesController@show ID {$id}: " . $e->getMessage());
            return redirect()->route('medidas-corporales.index')->with('error', 'Error al consultar la medida corporal.');
        }
    }

    /**
     * Muestra el formulario para editar una medida corporal existente.
     *
     * @param int $id ID del registro
     * @return View|RedirectResponse
     */
    public function edit(int $id): View|RedirectResponse
    {
        try {
            $response = $this->medidasCorporalesService->getMedidaCorporal($id);

            if (!($response['success'] ?? false) || empty($response['data'])) {
                return redirect()->route('medidas-corporales.index')->with('error', $this->apiMessage($response, 'Registro de medida corporal no encontrado.'));
            }

            $medidaCorporal = $response['data'];

            $animalesResponse = $this->animalesService->getAnimales(null, ['incluir_archivados' => true]);
            $rawAnimales      = $animalesResponse['data'] ?? [];
            $animales         = is_array($rawAnimales)
                ? (isset($rawAnimales['data']) && is_array($rawAnimales['data']) ? $rawAnimales['data'] : array_values(array_filter($rawAnimales, 'is_array')))
                : [];

            return view('medidas-corporales.edit', compact('medidaCorporal', 'animales'));
        } catch (\Exception $e) {
            Log::error("Error en MedidasCorporalesController@edit ID {$id}: " . $e->getMessage());
            return redirect()->route('medidas-corporales.index')->with('error', 'Error al cargar la información para edición.');
        }
    }

    /**
     * Actualiza una medida corporal existente.
     *
     * @param Request $request
     * @param int $id ID del registro
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validatedData = $request->validate([
            'animal_id'     => 'required|integer|min:1',
            'etapa_id'      => 'nullable|integer',
            'animal_etapa_id' => 'nullable|integer',
            'altura_hc'     => 'nullable|numeric|min:0|max:300',
            'altura_hg'     => 'nullable|numeric|min:0|max:300',
            'perimetro_pt'  => 'nullable|numeric|min:0|max:500',
            'perimetro_pca' => 'nullable|numeric|min:0|max:200',
            'longitud_lc'   => 'nullable|numeric|min:0|max:500',
            'longitud_lg'   => 'nullable|numeric|min:0|max:200',
            'anchura_ag'    => 'nullable|numeric|min:0|max:200',
        ], [
            'animal_id.required'  => 'El animal es requerido.',
            'animal_id.integer'   => 'El animal seleccionado no es válido.',
            'altura_hc.numeric'   => 'La altura a la cruz debe ser un número.',
            'altura_hc.max'       => 'La altura a la cruz no puede exceder 300 cm.',
            'altura_hg.numeric'   => 'La altura a la grupa debe ser un número.',
            'altura_hg.max'       => 'La altura a la grupa no puede exceder 300 cm.',
            'perimetro_pt.numeric'=> 'El perímetro torácico debe ser un número.',
            'perimetro_pt.max'    => 'El perímetro torácico no puede exceder 500 cm.',
            'perimetro_pca.numeric' => 'El perímetro de caña debe ser un número.',
            'perimetro_pca.max'   => 'El perímetro de caña no puede exceder 200 cm.',
            'longitud_lc.numeric' => 'La longitud corporal debe ser un número.',
            'longitud_lc.max'     => 'La longitud corporal no puede exceder 500 cm.',
            'longitud_lg.numeric' => 'La longitud de grupa debe ser un número.',
            'longitud_lg.max'     => 'La longitud de grupa no puede exceder 200 cm.',
            'anchura_ag.numeric'  => 'La anchura de grupa debe ser un número.',
            'anchura_ag.max'      => 'La anchura de grupa no puede exceder 200 cm.',
        ]);

        try {
            $response = $this->medidasCorporalesService->updateMedidaCorporal($id, $validatedData);

            if ($response['success'] ?? false) {
                return redirect()->route('medidas-corporales.index')
                    ->with('success', 'Medidas corporales actualizadas exitosamente.');
            }

            return back()->withInput()->with('error', $this->apiMessage($response, 'No se pudo actualizar la medida corporal.'));
        } catch (\Exception $e) {
            Log::error("Error en MedidasCorporalesController@update ID {$id}: " . $e->getMessage());
            return back()->withInput()->with('error', 'Error inesperado al actualizar las medidas corporales.');
        }
    }

    /**
     * Elimina una medida corporal.
     *
     * @param int $id ID del registro
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $response = $this->medidasCorporalesService->deleteMedidaCorporal($id);

            if ($response['success'] ?? false) {
                return redirect()->route('medidas-corporales.index')
                    ->with('success', 'Registro de medidas eliminado exitosamente.');
            }

            return redirect()->route('medidas-corporales.index')->with('error', $this->apiMessage($response, 'No se pudo eliminar el registro.'));
        } catch (\Exception $e) {
            Log::error("Error en MedidasCorporalesController@destroy ID {$id}: " . $e->getMessage());
            return redirect()->route('medidas-corporales.index')->with('error', 'Error al procesar la eliminación.');
        }
    }
}