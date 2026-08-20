<?php

namespace App\Http\Controllers;

use App\Services\Contracts\AnimalesServiceInterface;
use App\Services\Contracts\FincasServiceInterface;
use App\Services\Contracts\RebanosServiceInterface;
use App\Services\Contracts\VacunaServiceInterface;
use App\Services\Contracts\VacunacionServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VacunacionController extends Controller
{
    public function __construct(
        protected VacunacionServiceInterface $service,
        protected AnimalesServiceInterface $animalesService,
        protected VacunaServiceInterface $vacunaService,
        protected RebanosServiceInterface $rebanoService,
        protected FincasServiceInterface $fincaService
    ) {
    }

    /**
     * Extrae de forma estandarizada y segura los elementos de cualquier respuesta o array.
     */
    private function extractData(mixed $response): array
    {
        if (empty($response)) {
            return [];
        }

        // Si ya es una lista indexada de objetos/arrays [0 => [...], 1 => [...]]
        if (is_array($response) && array_is_list($response)) {
            return $response;
        }

        // Si viene envuelto en 'data'
        $data = is_array($response) && array_key_exists('data', $response) ? $response['data'] : $response;

        // Si viene doblemente envuelto en data.data (ej. formatCollection o paginación de Laravel)
        if (is_array($data) && array_key_exists('data', $data) && !isset($data['id'])) {
            $data = $data['data'];
        }

        return is_array($data) ? $data : [];
    }

    /**
     * Obtiene el mensaje de la API o usa uno de respaldo.
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
     * Muestra la lista de vacunaciones con filtros.
     */
    public function index(Request $request): View
    {
        $filters = $request->only([
            'vacuna_id',
            'rebano_id',
            'finca_id',
            'sexo',
            'etapa_id',
            'archivado',
            'fecha_inicio',
            'fecha_fin',
        ]);

        $response = $this->service->getList($filters);
        $vacunaciones = $this->extractData($response);

        $vacunas = $this->extractData($this->vacunaService->getAll());
        $rebanos = $this->extractData($this->rebanoService->getRebanos());
        $fincas  = $this->extractData($this->fincaService->getFincas());

        return view('vacunacion.index', compact('vacunaciones', 'vacunas', 'rebanos', 'fincas', 'filters'));
    }

    /**
     * Muestra el formulario para registrar una nueva jornada de vacunación.
     */
    public function create(): View
    {
        $vacunas = $this->extractData($this->vacunaService->getAll());
        $rebanos = $this->extractData($this->rebanoService->getRebanos());
        $fincas  = $this->extractData($this->fincaService->getFincas());

        return view('vacunacion.create', compact('vacunas', 'rebanos', 'fincas'));
    }

    /**
     * Carga vía AJAX los animales filtrados usando el servicio estándar de animales.
     */
    public function animalesElegibles(Request $request): JsonResponse
    {
        $request->validate([
            'finca_id'  => 'nullable|integer',
            'rebano_id' => 'nullable|integer',
            'sexo'      => 'nullable|in:M,H',
            'etapa_id'  => 'nullable|integer',
        ]);

        $filters = array_filter($request->only(['finca_id', 'sexo', 'etapa_id']));
        $rebanoId = $request->filled('rebano_id') ? (int) $request->input('rebano_id') : null;

        // Consumo directo y limpio del servicio de animales
        $response = $this->animalesService->getAnimales($rebanoId, $filters);
        $animales = $this->extractData($response);

        return response()->json([
            'success' => $response['success'] ?? false,
            'data'    => $animales,
            'message' => $this->apiMessage($response, 'No se pudieron cargar los animales.'),
        ]);
    }

    /**
     * Almacena una nueva jornada de vacunación individual o por lote.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vacuna_id'     => 'required|integer',
            'fecha'         => 'required|date',
            'dosis'         => 'nullable|numeric|min:0',
            'costo'         => 'nullable|numeric|min:0',
            'lote'          => 'nullable|string|max:50',
            'observacion'   => 'nullable|string',
            'animal_ids'    => 'required_without:animal_id|array|min:1',
            'animal_ids.*'  => 'integer',
            'animal_id'     => 'required_without:animal_ids|integer',
        ], [
            'vacuna_id.required'          => 'Debe seleccionar una vacuna válida.',
            'fecha.required'              => 'La fecha de vacunación es obligatoria.',
            'animal_ids.required_without' => 'Debe seleccionar al menos un animal para vacunar.',
            'animal_ids.min'              => 'Debe seleccionar al menos un animal para vacunar.',
            'animal_id.required_without'  => 'Debe indicar al menos un animal para vacunar.',
        ]);

        $response = $this->service->create($validated);

        if ($response['success'] ?? false) {
            $msg = is_string($response['message'] ?? null) ? $response['message'] : 'Vacunación registrada exitosamente.';
            return redirect()->route('vacunacion.index')->with('success', $msg);
        }

        return back()->withInput()->with('error', $this->apiMessage($response, 'No se pudo registrar la vacunación.'));
    }

    /**
     * Muestra el detalle de una vacunación.
     */
    public function show(int $id): View|RedirectResponse
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('vacunacion.index')->with('error', 'Vacunación no encontrada.');
        }

        $vacunacion = $response['data'] ?? [];
        return view('vacunacion.show', compact('vacunacion'));
    }

    /**
     * Muestra el formulario para editar una vacunación.
     */
    public function edit(int $id): View|RedirectResponse
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('vacunacion.index')->with('error', 'Vacunación no encontrada.');
        }

        $vacunacion = $response['data'] ?? [];
        $vacunas = $this->extractData($this->vacunaService->getAll());

        return view('vacunacion.edit', compact('vacunacion', 'vacunas'));
    }

    /**
     * Actualiza un registro de vacunación existente.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'vacuna_id'   => 'sometimes|required|integer',
            'fecha'       => 'sometimes|required|date',
            'dosis'       => 'nullable|numeric|min:0',
            'costo'       => 'nullable|numeric|min:0',
            'lote'        => 'nullable|string|max:50',
            'observacion' => 'nullable|string',
        ]);

        $response = $this->service->update($id, $validated);

        if ($response['success'] ?? false) {
            return redirect()->route('vacunacion.index')->with('success', 'Vacunación actualizada exitosamente.');
        }

        return back()->withInput()->with('error', $this->apiMessage($response, 'No se pudo actualizar la vacunación.'));
    }

    /**
     * Elimina un registro de vacunación.
     */
    public function destroy(int $id): RedirectResponse
    {
        $response = $this->service->eliminar($id);

        if ($response['success'] ?? false) {
            return redirect()->route('vacunacion.index')->with('success', 'Registro de vacunación eliminado exitosamente.');
        }

        return redirect()->route('vacunacion.index')->with('error', $this->apiMessage($response, 'No se pudo eliminar la vacunación.'));
    }
}
