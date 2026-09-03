<?php

namespace App\Http\Controllers;

use App\Services\Contracts\FincasServiceInterface;
use App\Services\Contracts\LactanciaServiceInterface;
use App\Services\Contracts\LecheServiceInterface;
use App\Services\Contracts\RebanosServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Controlador para el Control y Registro de Producción de Leche.
 * 
 * Administra los pesajes de producción e interactúa exclusivamente
 * con la API v2.
 */
class LecheController extends Controller
{
    public function __construct(
        protected LecheServiceInterface $lecheService,
        protected LactanciaServiceInterface $lactanciaService,
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
     * Muestra la lista de registros de producción de leche con estadísticas y filtros.
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function index(Request $request): View|RedirectResponse
    {
        try {
            $lactanciaId = $request->query('lactancia_id') ? (int) $request->query('lactancia_id') : null;
            $fechaInicio = $request->query('fecha_inicio');
            $fechaFin    = $request->query('fecha_fin');

            $response = $this->lecheService->getRegistrosLeche($lactanciaId, $fechaInicio, $fechaFin);

            if (!($response['success'] ?? false)) {
                return redirect()->route('dashboard')->with('error', $this->apiMessage($response, 'Error al consultar registros de leche.'));
            }

            // Cargar lactancias para el selector de filtros
            $lactanciasResponse = $this->lactanciaService->getLactancias();
            $rawLactancias      = $lactanciasResponse['data'] ?? [];
            $lactancias         = is_array($rawLactancias)
                ? (isset($rawLactancias['data']) && is_array($rawLactancias['data']) ? $rawLactancias['data'] : array_values(array_filter($rawLactancias, 'is_array')))
                : [];

            $lactanciaMap = collect($lactancias)
                ->filter(fn ($lact) => isset($lact['id']))
                ->mapWithKeys(function ($lact) {
                    $animalId     = data_get($lact, 'animal.id') ?? data_get($lact, 'etapa_animal.animal_id') ?? $lact['animal_id'] ?? null;
                    $animalNombre = data_get($lact, 'animal.nombre') ?? ($animalId ? ('Animal #' . $animalId) : 'Animal no disponible');
                    $animalCodigo = data_get($lact, 'animal.codigo_animal') ?? '';
                    $rebanoId     = data_get($lact, 'animal.rebano_id') ?? data_get($lact, 'animal.rebano.id');
                    $fincaId      = data_get($lact, 'animal.rebano.finca_id') ?? data_get($lact, 'animal.rebano.finca.id');
                    return [(int) $lact['id'] => [
                        'animal_nombre' => $animalNombre,
                        'animal_codigo' => $animalCodigo,
                        'animal_id'     => $animalId,
                        'rebano_id'     => $rebanoId,
                        'finca_id'      => $fincaId,
                    ]];
                })
                ->all();

            $rawRegistros = $response['data'] ?? [];
            $registrosLeche = collect(is_array($rawRegistros) ? array_values(array_filter($rawRegistros, 'is_array')) : [])
                ->map(function ($registro) use ($lactanciaMap) {
                    $lactanciaIdRecord = (int) ($registro['lactancia_id'] ?? 0);
                    $meta = $lactanciaMap[$lactanciaIdRecord] ?? null;
                    $registro['animal_nombre'] = data_get($registro, 'animal.nombre')
                        ?? data_get($registro, 'lactancia.animal.nombre')
                        ?? ($meta['animal_nombre'] ?? 'Animal no disponible');
                    $registro['animal_codigo'] = data_get($registro, 'animal.codigo_animal')
                        ?? data_get($registro, 'lactancia.animal.codigo_animal')
                        ?? ($meta['animal_codigo'] ?? '');
                    $registro['finca_id'] = data_get($registro, 'finca_id')
                        ?? data_get($registro, 'animal.rebano.finca_id')
                        ?? ($meta['finca_id'] ?? null);
                    $registro['rebano_id'] = data_get($registro, 'rebano_id')
                        ?? data_get($registro, 'animal.rebano_id')
                        ?? ($meta['rebano_id'] ?? null);
                    return $registro;
                })->all();

            $fincasRes = $this->fincasService->getFincas(['incluir_archivados' => true]);
            $fincas = ($fincasRes['success'] ?? false) ? ($fincasRes['data']['data'] ?? $fincasRes['data'] ?? []) : [];

            $rebanosRes = $this->rebanosService->getRebanos(['incluir_archivados' => true]);
            $rebanos = ($rebanosRes['success'] ?? false) ? ($rebanosRes['data']['data'] ?? $rebanosRes['data'] ?? []) : [];

            return view('leche.index', compact('registrosLeche', 'lactancias', 'fincas', 'rebanos', 'lactanciaId'));
        } catch (\Exception $e) {
            Log::error('Error en LecheController@index: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error al cargar los registros de producción de leche.');
        }
    }

    /**
     * Muestra el formulario para crear un nuevo registro de leche.
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        $lactanciaId = $request->query('lactancia_id') ? (int) $request->query('lactancia_id') : null;

        $lactanciasResponse = $this->lactanciaService->getLactancias();
        $rawLactancias      = $lactanciasResponse['data'] ?? [];
        $lactancias         = is_array($rawLactancias)
            ? (isset($rawLactancias['data']) && is_array($rawLactancias['data']) ? $rawLactancias['data'] : array_values(array_filter($rawLactancias, 'is_array')))
            : [];

        return view('leche.create', compact('lactancias', 'lactanciaId'));
    }

    /**
     * Almacena un nuevo pesaje de leche.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'fecha_pesaje' => 'required|date',
            'pesaje_total' => 'required|numeric|min:0.01',
            'lactancia_id' => 'required|integer|min:1',
        ], [
            'fecha_pesaje.required' => 'La fecha de pesaje es requerida.',
            'fecha_pesaje.date'     => 'La fecha de pesaje debe ser una fecha válida.',
            'pesaje_total.required' => 'La cantidad de leche es requerida.',
            'pesaje_total.numeric'  => 'La cantidad de leche debe ser un número.',
            'pesaje_total.min'      => 'La cantidad de leche debe ser mayor a 0.',
            'lactancia_id.required' => 'El período de lactancia es requerido.',
            'lactancia_id.integer'  => 'El período de lactancia seleccionado no es válido.',
        ]);

        try {
            $response = $this->lecheService->createRegistroLeche($validatedData);

            if ($response['success'] ?? false) {
                return redirect()->route('leche.index', ['lactancia_id' => $validatedData['lactancia_id']])
                    ->with('success', 'Registro de producción de leche guardado exitosamente.');
            }

            return back()->withInput()->with('error', $this->apiMessage($response, 'No se pudo registrar la producción de leche.'));
        } catch (\Exception $e) {
            Log::error('Error en LecheController@store: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error inesperado al guardar el registro de leche.');
        }
    }

    /**
     * Muestra el detalle de un pesaje de leche.
     *
     * @param int $id ID del registro de leche
     * @return View|RedirectResponse
     */
    public function show(int $id): View|RedirectResponse
    {
        try {
            $response = $this->lecheService->getRegistroLeche($id);

            if (!($response['success'] ?? false) || empty($response['data'])) {
                return redirect()->route('leche.index')->with('error', $this->apiMessage($response, 'Registro de leche no encontrado.'));
            }

            $registroLeche = $response['data'];

            return view('leche.show', compact('registroLeche'));
        } catch (\Exception $e) {
            Log::error("Error en LecheController@show ID {$id}: " . $e->getMessage());
            return redirect()->route('leche.index')->with('error', 'Error al consultar el pesaje de leche.');
        }
    }

    /**
     * Muestra el formulario para editar un pesaje de leche existente.
     *
     * @param int $id ID del registro de leche
     * @return View|RedirectResponse
     */
    public function edit(int $id): View|RedirectResponse
    {
        try {
            $response = $this->lecheService->getRegistroLeche($id);

            if (!($response['success'] ?? false) || empty($response['data'])) {
                return redirect()->route('leche.index')->with('error', $this->apiMessage($response, 'Registro de leche no encontrado.'));
            }

            $registroLeche = $response['data'];

            $lactanciasResponse = $this->lactanciaService->getLactancias();
            $rawLactancias      = $lactanciasResponse['data'] ?? [];
            $lactancias         = is_array($rawLactancias)
                ? (isset($rawLactancias['data']) && is_array($rawLactancias['data']) ? $rawLactancias['data'] : array_values(array_filter($rawLactancias, 'is_array')))
                : [];

            return view('leche.edit', compact('registroLeche', 'lactancias'));
        } catch (\Exception $e) {
            Log::error("Error en LecheController@edit ID {$id}: " . $e->getMessage());
            return redirect()->route('leche.index')->with('error', 'Error al cargar la información para edición.');
        }
    }

    /**
     * Actualiza un pesaje de leche existente.
     *
     * @param Request $request
     * @param int $id ID del registro de leche
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validatedData = $request->validate([
            'fecha_pesaje' => 'required|date',
            'pesaje_total' => 'required|numeric|min:0.01',
            'lactancia_id' => 'required|integer|min:1',
        ], [
            'fecha_pesaje.required' => 'La fecha de pesaje es requerida.',
            'fecha_pesaje.date'     => 'La fecha de pesaje debe ser una fecha válida.',
            'pesaje_total.required' => 'La cantidad de leche es requerida.',
            'pesaje_total.numeric'  => 'La cantidad de leche debe ser un número.',
            'pesaje_total.min'      => 'La cantidad de leche debe ser mayor a 0.',
            'lactancia_id.required' => 'El período de lactancia es requerido.',
            'lactancia_id.integer'  => 'El período de lactancia seleccionado no es válido.',
        ]);

        try {
            $response = $this->lecheService->updateRegistroLeche($id, $validatedData);

            if ($response['success'] ?? false) {
                return redirect()->route('leche.index')
                    ->with('success', 'Registro de leche actualizado exitosamente.');
            }

            return back()->withInput()->with('error', $this->apiMessage($response, 'No se pudo actualizar el registro de leche.'));
        } catch (\Exception $e) {
            Log::error("Error en LecheController@update ID {$id}: " . $e->getMessage());
            return back()->withInput()->with('error', 'Error inesperado al actualizar el pesaje de leche.');
        }
    }

    /**
     * Elimina un pesaje de leche.
     *
     * @param int $id ID del registro de leche
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $response = $this->lecheService->deleteRegistroLeche($id);

            if ($response['success'] ?? false) {
                return redirect()->route('leche.index')
                    ->with('success', 'Registro de producción de leche eliminado exitosamente.');
            }

            return redirect()->route('leche.index')->with('error', $this->apiMessage($response, 'No se pudo eliminar el registro de leche.'));
        } catch (\Exception $e) {
            Log::error("Error en LecheController@destroy ID {$id}: " . $e->getMessage());
            return redirect()->route('leche.index')->with('error', 'Error al procesar la eliminación del registro.');
        }
    }
}