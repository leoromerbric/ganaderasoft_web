<?php

namespace App\Http\Controllers;

use App\Services\Contracts\RegistroCeloServiceInterface;
use Illuminate\Http\Request;

class RegistroCeloController extends Controller
{
    public function __construct(protected RegistroCeloServiceInterface $service) {}

    private function isFemale(array $animal): bool
    {
        $sexo = strtoupper((string) ($animal['Sexo'] ?? $animal['sexo'] ?? ''));
        if ($sexo !== '') {
            return in_array($sexo, ['F', 'H', 'FEMENINO', 'HEMBRA'], true);
        }

        $label = strtolower(trim((string) ($animal['sexo_label'] ?? $animal['genero'] ?? '')));
        return in_array($label, ['femenino', 'hembra'], true);
    }

    private function filterFemaleAnimals(array $animales): array
    {
        return array_values(array_filter($animales, fn (array $animal) => $this->isFemale($animal)));
    }

    public function index(Request $request)
    {
        $animalId    = $request->query('animal_id');
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin    = $request->query('fecha_fin');

        $response = $this->service->getList($animalId, $fechaInicio, $fechaFin);
        $registros = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $animales  = $this->filterFemaleAnimals($this->service->getAnimales());

        return view('registro-celo.index', compact('registros', 'animales', 'animalId', 'fechaInicio', 'fechaFin'));
    }

    public function create()
    {
        $animales = $this->filterFemaleAnimals($this->service->getAnimales());
        return view('registro-celo.create', compact('animales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'celo_fecha'      => 'required|date',
            'celo_observacon' => 'nullable|string|max:100',
            'celo_etapa_anid' => 'required|integer',
            'celo_etapa_etid' => 'required|integer',
        ], [
            'celo_fecha.required'      => 'La fecha de celo es requerida.',
            'celo_etapa_anid.required' => 'El animal es requerido.',
            'celo_etapa_etid.required' => 'La etapa del animal es requerida.',
        ]);

        $response = $this->service->create($request->only(['celo_fecha', 'celo_observacon', 'celo_etapa_anid', 'celo_etapa_etid']));

        if ($response['success'] ?? false) {
            return redirect()->route('registro-celo.index')->with('success', 'Registro de celo creado exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al crear el registro.');
    }

    public function show(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('registro-celo.index')->with('error', 'Registro no encontrado.');
        }
        $registro = $response['data'];
        return view('registro-celo.show', compact('registro'));
    }

    public function edit(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('registro-celo.index')->with('error', 'Registro no encontrado.');
        }
        $registro = $response['data'];
        $animales = $this->filterFemaleAnimals($this->service->getAnimales());
        return view('registro-celo.edit', compact('registro', 'animales'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'celo_fecha'      => 'required|date',
            'celo_observacon' => 'nullable|string|max:100',
        ], [
            'celo_fecha.required' => 'La fecha de celo es requerida.',
        ]);

        $response = $this->service->update($id, $request->only(['celo_fecha', 'celo_observacon']));

        if ($response['success'] ?? false) {
            return redirect()->route('registro-celo.index')->with('success', 'Registro de celo actualizado exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al actualizar.');
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('registro-celo.index')->with('success', 'Registro de celo eliminado.');
        }
        return redirect()->route('registro-celo.index')->with('error', $response['message'] ?? 'Error al eliminar.');
    }
}
