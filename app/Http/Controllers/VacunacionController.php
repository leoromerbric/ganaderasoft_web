<?php

namespace App\Http\Controllers;

use App\Services\Contracts\VacunacionServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VacunacionController extends Controller
{
    public function __construct(protected VacunacionServiceInterface $service)
    {
    }

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

    public function index(Request $request)
    {
        $filters = $request->only(['vacuna_id', 'rebano_id', 'fecha_inicio', 'fecha_fin']);
        $response = $this->service->getList($filters);

        $vacunaciones = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $vacunas = $this->service->getVacunas();
        $rebanos = $this->service->getRebanos();

        return view('vacunacion.index', compact('vacunaciones', 'vacunas', 'rebanos', 'filters'));
    }

    public function create()
    {
        $vacunas = $this->service->getVacunas();
        $casas = $this->service->getCasasComerciales();
        $animales = $this->service->getAnimales();
        $rebanos = $this->service->getRebanos();

        return view('vacunacion.create', compact('vacunas', 'casas', 'animales', 'rebanos'));
    }

    public function preview(Request $request)
    {
        $request->validate([
            'vacunacion_vacuna_id' => 'required|integer',
            'vacunacion_rebano_id' => 'required|integer',
            'vacunacion_modo_seleccion' => 'required|in:todos_rebano,lista_animales,filtros',
            'vacunacion_animal_ids' => 'nullable|array',
            'vacunacion_animal_ids.*' => 'integer',
            'vacunacion_filtros' => 'nullable|array',
            'vacunacion_costo_dosis' => 'required|numeric|min:0',
            'vacunacion_fecha' => 'required|date',
        ]);

        return response()->json($this->service->preview($request->all()));
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);
        $response = $this->service->create($validated);

        if ($response['success'] ?? false) {
            return redirect()->route('vacunacion.index')->with('success', 'Vacunación registrada exitosamente.');
        }

        return back()->withInput()->with('error', $this->apiMessage($response, 'No se pudo registrar la vacunación.'));
    }

    public function show(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('vacunacion.index')->with('error', 'Vacunación no encontrada.');
        }

        $vacunacion = $response['data'];
        return view('vacunacion.show', compact('vacunacion'));
    }

    public function edit(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('vacunacion.index')->with('error', 'Vacunación no encontrada.');
        }

        $vacunacion = $response['data'];
        $vacunas = $this->service->getVacunas();
        $casas = $this->service->getCasasComerciales();
        $animales = $this->service->getAnimales();
        $rebanos = $this->service->getRebanos();

        return view('vacunacion.edit', compact('vacunacion', 'vacunas', 'casas', 'animales', 'rebanos'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $this->validateRequest($request);
        $response = $this->service->update($id, $validated);

        if ($response['success'] ?? false) {
            return redirect()->route('vacunacion.index')->with('success', 'Vacunación actualizada exitosamente.');
        }

        return back()->withInput()->with('error', $this->apiMessage($response, 'No se pudo actualizar la vacunación.'));
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);

        if ($response['success'] ?? false) {
            return redirect()->route('vacunacion.index')->with('success', 'Vacunación eliminada.');
        }

        return redirect()->route('vacunacion.index')->with('error', $this->apiMessage($response, 'No se pudo eliminar la vacunación.'));
    }

    private function validateRequest(Request $request): array
    {
        $validated = $request->validate([
            'vacunacion_vacuna_id' => 'required|integer',
            'vacunacion_casa_id' => 'nullable|integer',
            'vacunacion_rebano_id' => 'required|integer',
            'vacunacion_modo_seleccion' => 'required|in:todos_rebano,lista_animales,filtros',
            'vacunacion_animal_ids' => 'nullable|array',
            'vacunacion_animal_ids.*' => 'integer',
            'vacunacion_filtros' => 'nullable|array',
            'vacunacion_filtros.sexo' => 'nullable|in:H,M',
            'vacunacion_filtros.nombre_like' => 'nullable|string',
            'vacunacion_filtros.codigo_like' => 'nullable|string',
            'vacunacion_filtros.edad_min_dias' => 'nullable|integer|min:0',
            'vacunacion_filtros.edad_max_dias' => 'nullable|integer|min:0',
            'vacunacion_filtros.etapa_id' => 'nullable|integer|min:1',
            'vacunacion_costo_dosis' => 'required|numeric|min:0',
            'vacunacion_fecha' => 'required|date',
            'vacunacion_observacion' => 'nullable|string',
        ]);

        if (($validated['vacunacion_modo_seleccion'] ?? null) === 'lista_animales' && empty($validated['vacunacion_animal_ids'])) {
            throw ValidationException::withMessages([
                'vacunacion_animal_ids' => 'Seleccione al menos un animal para modo lista.',
            ]);
        }

        return $validated;
    }
}
