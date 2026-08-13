<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ReproduccionAnimalServiceInterface;
use Illuminate\Http\Request;

class ReproduccionAnimalController extends Controller
{
    public function __construct(protected ReproduccionAnimalServiceInterface $service) {}

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
        $tipo        = $request->query('tipo');
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin    = $request->query('fecha_fin');

        $response       = $this->service->getList($animalId, $tipo, $fechaInicio, $fechaFin);
        $data           = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $reproducciones = (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) ? $data['data'] : $data;
        $animales       = $this->filterFemaleAnimals($this->service->getAnimales());

        return view('reproduccion-animal.index', compact('reproducciones', 'animales', 'animalId', 'tipo', 'fechaInicio', 'fechaFin'));
    }

    public function create()
    {
        $animales = $this->filterFemaleAnimals($this->service->getAnimales());
        return view('reproduccion-animal.create', compact('animales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha'       => 'required|date',
            'tipo'        => 'nullable|string|max:8',
            'observacion' => 'nullable|string|max:60',
            'animal_id'   => 'required|integer',
            'etapa_id'    => 'required|integer',
        ], [
            'fecha.required'     => 'La fecha de reproducción es requerida.',
            'animal_id.required' => 'El animal es requerido.',
            'etapa_id.required'  => 'La etapa del animal es requerida.',
        ]);

        $data = [
            'fecha_reproduccion' => $request->input('fecha'),
            'tipo_reproduccion'  => $request->input('tipo'),
            'observacion'        => $request->input('observacion'),
            'animal_id'          => $request->input('animal_id'),
            'etapa_id'           => $request->input('etapa_id'),
        ];

        $response = $this->service->create($data);

        if ($response['success'] ?? false) {
            return redirect()->route('reproduccion-animal.index')->with('success', 'Reproducción registrada exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al crear el registro.');
    }

    public function show(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('reproduccion-animal.index')->with('error', 'Registro no encontrado.');
        }
        $reproduccion = $response['data'];
        return view('reproduccion-animal.show', compact('reproduccion'));
    }

    public function edit(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('reproduccion-animal.index')->with('error', 'Registro no encontrado.');
        }
        $reproduccion = $response['data'];
        $animales = $this->filterFemaleAnimals($this->service->getAnimales());
        return view('reproduccion-animal.edit', compact('reproduccion', 'animales'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'fecha'       => 'required|date',
            'tipo'        => 'nullable|string|max:8',
            'observacion' => 'nullable|string|max:60',
        ], [
            'fecha.required' => 'La fecha de reproducción es requerida.',
        ]);

        $data = [
            'fecha_reproduccion' => $request->input('fecha'),
            'tipo_reproduccion'  => $request->input('tipo'),
            'observacion'        => $request->input('observacion'),
        ];

        $response = $this->service->update($id, $data);

        if ($response['success'] ?? false) {
            return redirect()->route('reproduccion-animal.index')->with('success', 'Reproducción actualizada exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al actualizar.');
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('reproduccion-animal.index')->with('success', 'Reproducción eliminada.');
        }
        return redirect()->route('reproduccion-animal.index')->with('error', $response['message'] ?? 'Error al eliminar.');
    }
}
