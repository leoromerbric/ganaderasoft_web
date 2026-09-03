<?php

namespace App\Http\Controllers;

use App\Services\Contracts\AnimalesServiceInterface;
use App\Services\Contracts\FincasServiceInterface;
use App\Services\Contracts\LactanciaServiceInterface;
use App\Services\Contracts\RebanosServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Controlador para la Gestión de Períodos de Lactancia.
 * 
 * Administra los ciclos de lactancia e interactúa exclusivamente
 * con la API v2.
 */
class LactanciaController extends Controller
{
    public function __construct(
        protected LactanciaServiceInterface $lactanciaService,
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
     * Evalúa si un registro de animal corresponde al sexo hembra.
     *
     * @param array<string, mixed> $animal
     * @return bool
     */
    private function isFemale(array $animal): bool
    {
        $sexo = strtoupper((string) ($animal['sexo'] ?? ''));
        return in_array($sexo, ['F', 'H', 'FEMENINO', 'HEMBRA'], true);
    }

    /**
     * Extrae la lista de animales del catálogo de forma segura.
     *
     * @param array<string, mixed> $filters
     * @return array<int, array<string, mixed>>
     */
    private function getAnimalesCatalogo(array $filters = []): array
    {
        $animalesResponse = $this->animalesService->getAnimales(null, $filters);
        $raw = $animalesResponse['data'] ?? [];

        return is_array($raw)
            ? (isset($raw['data']) && is_array($raw['data']) ? $raw['data'] : array_values(array_filter($raw, 'is_array')))
            : [];
    }

    /**
     * Muestra la lista de períodos de lactancia con filtros y estadísticas.
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function index(Request $request): View|RedirectResponse
    {
        try {
            $animalId = $request->query('animal_id') ? (int) $request->query('animal_id') : null;
            $activa   = $request->query('activa') !== null ? (bool) $request->query('activa') : null;

            $response = $this->lactanciaService->getLactancias($animalId, $activa);

            if (!($response['success'] ?? false)) {
                return redirect()->route('dashboard')->with('error', $this->apiMessage($response, 'Error al consultar lactancias.'));
            }

            $animales      = $this->getAnimalesCatalogo(['incluir_archivados' => true]);
            $animalesPorId = collect($animales)
                ->filter(fn ($a) => isset($a['id']))
                ->keyBy(fn ($a) => (int) $a['id']);

            $rawLactancias = $response['data'] ?? [];
            $lactancias = collect(is_array($rawLactancias) ? array_values(array_filter($rawLactancias, 'is_array')) : [])
                ->map(function ($lactancia) use ($animalesPorId) {
                    $animalIdRegistro = data_get($lactancia, 'animal.id') ?? data_get($lactancia, 'etapa_animal.animal_id') ?? $lactancia['animal_id'] ?? null;
                    $animal = $animalIdRegistro ? $animalesPorId->get((int) $animalIdRegistro, []) : [];

                    $rebanoId = data_get($lactancia, 'animal.rebano_id') ?? ($animal['rebano_id'] ?? data_get($animal, 'rebano.id'));
                    $fincaId  = data_get($lactancia, 'animal.rebano.finca_id') ?? data_get($animal, 'rebano.finca_id') ?? data_get($animal, 'rebano.finca.id');

                    $animalNombre = data_get($lactancia, 'animal.nombre')
                        ?? ($animal['nombre'] ?? ($animalIdRegistro !== null ? ('Animal #' . $animalIdRegistro) : 'Animal no disponible'));
                    $animalCodigo = data_get($lactancia, 'animal.codigo_animal')
                        ?? ($animal['codigo_animal'] ?? '');

                    $lactancia['animal_nombre'] = $animalNombre;
                    $lactancia['animal_codigo'] = $animalCodigo;
                    $lactancia['animal_id']     = $animalIdRegistro;
                    $lactancia['rebano_id']     = $rebanoId;
                    $lactancia['finca_id']      = $fincaId;
                    return $lactancia;
                })->all();

            $fincasRes = $this->fincasService->getFincas(['incluir_archivados' => true]);
            $fincas = ($fincasRes['success'] ?? false) ? ($fincasRes['data']['data'] ?? $fincasRes['data'] ?? []) : [];

            $rebanosRes = $this->rebanosService->getRebanos(['incluir_archivados' => true]);
            $rebanos = ($rebanosRes['success'] ?? false) ? ($rebanosRes['data']['data'] ?? $rebanosRes['data'] ?? []) : [];

            return view('lactancia.index', compact('lactancias', 'animales', 'fincas', 'rebanos', 'animalId'));
        } catch (\Exception $e) {
            Log::error('Error en LactanciaController@index: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error al cargar los períodos de lactancia.');
        }
    }

    /**
     * Muestra el formulario para registrar un nuevo período de lactancia.
     *
     * @return View
     */
    public function create(): View
    {
        $animales = array_values(array_filter($this->getAnimalesCatalogo(), fn ($animal) => $this->isFemale($animal)));

        return view('lactancia.create', compact('animales'));
    }

    /**
     * Almacena un nuevo período de lactancia.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'fecha_inicio' => 'required|date',
            'animal_id'    => 'required|integer|min:1',
            'etapa_id'     => 'required|integer|min:1',
            'fecha_fin'    => 'nullable|date|after_or_equal:fecha_inicio',
            'secado'       => 'nullable|date',
        ], [
            'fecha_inicio.required'    => 'La fecha de inicio es requerida.',
            'fecha_inicio.date'        => 'La fecha de inicio debe ser una fecha válida.',
            'animal_id.required'       => 'El animal es requerido.',
            'animal_id.integer'        => 'El animal seleccionado no es válido.',
            'etapa_id.required'        => 'La etapa es requerida.',
            'etapa_id.integer'         => 'La etapa seleccionada no es válida.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
            'secado.date'              => 'La fecha de secado debe ser una fecha válida.',
        ]);

        try {
            $response = $this->lactanciaService->createLactancia($validatedData);

            if ($response['success'] ?? false) {
                return redirect()->route('lactancia.index')
                    ->with('success', 'Período de lactancia registrado exitosamente.');
            }

            return back()->withInput()->with('error', $this->apiMessage($response, 'No se pudo registrar la lactancia.'));
        } catch (\Exception $e) {
            Log::error('Error en LactanciaController@store: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error inesperado al registrar el período de lactancia.');
        }
    }

    /**
     * Muestra el detalle de un período de lactancia.
     *
     * @param int $id ID de la lactancia
     * @return View|RedirectResponse
     */
    public function show(int $id): View|RedirectResponse
    {
        try {
            $response = $this->lactanciaService->getLactancia($id);

            if (!($response['success'] ?? false) || empty($response['data'])) {
                return redirect()->route('lactancia.index')->with('error', $this->apiMessage($response, 'No se pudo cargar la lactancia.'));
            }

            $lactancia = $response['data'];

            return view('lactancia.show', compact('lactancia'));
        } catch (\Exception $e) {
            Log::error("Error en LactanciaController@show ID {$id}: " . $e->getMessage());
            return redirect()->route('lactancia.index')->with('error', 'Error al consultar la lactancia.');
        }
    }

    /**
     * Muestra el formulario para editar un período de lactancia.
     *
     * @param int $id ID de la lactancia
     * @return View|RedirectResponse
     */
    public function edit(int $id): View|RedirectResponse
    {
        try {
            $response = $this->lactanciaService->getLactancia($id);

            if (!($response['success'] ?? false) || empty($response['data'])) {
                return redirect()->route('lactancia.index')->with('error', $this->apiMessage($response, 'No se pudo cargar la lactancia.'));
            }

            $lactancia = $response['data'];
            $animales  = array_values(array_filter($this->getAnimalesCatalogo(['incluir_archivados' => true]), fn ($animal) => $this->isFemale($animal)));

            return view('lactancia.edit', compact('lactancia', 'animales'));
        } catch (\Exception $e) {
            Log::error("Error en LactanciaController@edit ID {$id}: " . $e->getMessage());
            return redirect()->route('lactancia.index')->with('error', 'Error al cargar la información para edición.');
        }
    }

    /**
     * Actualiza un período de lactancia existente.
     *
     * @param Request $request
     * @param int $id ID de la lactancia
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validatedData = $request->validate([
            'fecha_inicio' => 'required|date',
            'animal_id'    => 'required|integer|min:1',
            'etapa_id'     => 'required|integer|min:1',
            'fecha_fin'    => 'nullable|date|after_or_equal:fecha_inicio',
            'secado'       => 'nullable|date',
        ], [
            'fecha_inicio.required'    => 'La fecha de inicio es requerida.',
            'fecha_inicio.date'        => 'La fecha de inicio debe ser una fecha válida.',
            'animal_id.required'       => 'El animal es requerido.',
            'animal_id.integer'        => 'El animal seleccionado no es válido.',
            'etapa_id.required'        => 'La etapa es requerida.',
            'etapa_id.integer'         => 'La etapa seleccionada no es válida.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
            'secado.date'              => 'La fecha de secado debe ser una fecha válida.',
        ]);

        try {
            $response = $this->lactanciaService->updateLactancia($id, $validatedData);

            if ($response['success'] ?? false) {
                return redirect()->route('lactancia.index')
                    ->with('success', 'Período de lactancia actualizado exitosamente.');
            }

            return back()->withInput()->with('error', $this->apiMessage($response, 'No se pudo actualizar la lactancia.'));
        } catch (\Exception $e) {
            Log::error("Error en LactanciaController@update ID {$id}: " . $e->getMessage());
            return back()->withInput()->with('error', 'Error inesperado al actualizar la lactancia.');
        }
    }

    /**
     * Elimina un período de lactancia.
     *
     * @param int $id ID de la lactancia
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $response = $this->lactanciaService->deleteLactancia($id);

            if ($response['success'] ?? false) {
                return redirect()->route('lactancia.index')
                    ->with('success', 'Período de lactancia eliminado exitosamente.');
            }

            return redirect()->route('lactancia.index')->with('error', $this->apiMessage($response, 'No se pudo eliminar la lactancia.'));
        } catch (\Exception $e) {
            Log::error("Error en LactanciaController@destroy ID {$id}: " . $e->getMessage());
            return redirect()->route('lactancia.index')->with('error', 'Error al procesar la eliminación.');
        }
    }

    /**
     * Endpoint AJAX para obtener la etapa actual del animal seleccionado.
     *
     * @param Request $request
     * @param int $id ID del animal
     * @return JsonResponse
     */
    public function getAnimalEtapa(Request $request, int $id): JsonResponse
    {
        try {
            $animalResponse = $this->animalesService->getAnimal($id);

            if (!($animalResponse['success'] ?? false) || empty($animalResponse['data'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Animal no encontrado'
                ], 404);
            }

            $animal      = $animalResponse['data'];
            $etapaActual = data_get($animal, 'etapa_actual') ?? data_get($animal, 'etapaActual');

            return response()->json([
                'success' => true,
                'data'    => [
                    'animal'       => $animal,
                    'etapa_actual' => $etapaActual
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("Error en getAnimalEtapa para el animal ID {$id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener etapa del animal'
            ], 500);
        }
    }
}