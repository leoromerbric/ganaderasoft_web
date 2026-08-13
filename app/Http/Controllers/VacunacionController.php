<?php

namespace App\Http\Controllers;

use App\Services\Contracts\VacunacionServiceInterface;
use Illuminate\Http\Request;

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

        $data = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $vacunaciones = (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) ? $data['data'] : $data;
        $vacunas = $this->service->getVacunas();
        $rebanos = $this->service->getRebanos();

        return view('vacunacion.index', compact('vacunaciones', 'vacunas', 'rebanos', 'filters'));
    }

    public function create()
    {
        $vacunas = $this->service->getVacunas();
        $rebanos = $this->service->getRebanos();
        $etapas = $this->service->getEtapas();

        return view('vacunacion.create', compact('vacunas', 'rebanos', 'etapas'));
    }

    public function animalesElegibles(Request $request)
    {
        $request->validate([
            'rebano_id' => 'required|integer',
            'sexo' => 'nullable|in:M,H',
            'etapa_id' => 'nullable|integer',
        ]);

        $response = $this->service->getAnimalesElegibles($request->only(['rebano_id', 'sexo', 'etapa_id']));

        return response()->json([
            'success' => $response['success'] ?? false,
            'data' => ($response['success'] ?? false) ? ($response['data'] ?? []) : [],
            'message' => $this->apiMessage($response, 'No se pudieron cargar los animales.'),
        ]);
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
        $rebanos = $this->service->getRebanos();
        $etapas = $this->service->getEtapas();

        return view('vacunacion.edit', compact('vacunacion', 'vacunas', 'rebanos', 'etapas'));
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
        return $request->validate([
            'vacunacion_vacuna_id' => 'required|integer',
            'vacunacion_rebano_id' => 'required|integer',
            'vacunacion_animal_ids' => 'required|array|min:1',
            'vacunacion_animal_ids.*' => 'integer',
            'vacunacion_filtros' => 'nullable|array',
            'vacunacion_filtros.sexo' => 'nullable|in:M,H',
            'vacunacion_filtros.etapa_id' => 'nullable|integer|min:1',
            'vacunacion_costo_dosis' => 'required|numeric|min:0',
            'vacunacion_fecha' => 'required|date',
            'vacunacion_observacion' => 'nullable|string',
        ], [
            'vacunacion_animal_ids.required' => 'Seleccione al menos un animal para vacunar.',
            'vacunacion_animal_ids.min' => 'Seleccione al menos un animal para vacunar.',
        ]);
    }
}
