<?php

namespace App\Http\Controllers;

use App\Services\Contracts\PalpacionServiceInterface;
use Illuminate\Http\Request;

class PalpacionController extends Controller
{
    public function __construct(protected PalpacionServiceInterface $service) {}

    private function isFemale(array $animal): bool
    {
        $sexo = strtoupper((string)($animal['Sexo'] ?? $animal['sexo'] ?? ''));
        if ($sexo !== '') {
            return in_array($sexo, ['F', 'H', 'FEMENINO', 'HEMBRA'], true);
        }

        $label = strtolower(trim((string)($animal['sexo_label'] ?? $animal['genero'] ?? '')));
        return in_array($label, ['femenino', 'hembra'], true);
    }

    private function filterFemaleAnimals(array $animales): array
    {
        return array_values(array_filter($animales, fn (array $animal) => $this->isFemale($animal)));
    }

    private function isVetOrTech(array $persona): bool
    {
        $tipoVal = data_get($persona, 'Tipo_Trabajador') ?? data_get($persona, 'tipo_trabajador') ?? data_get($persona, 'personal.Tipo_Trabajador') ?? '';
        if (is_array($tipoVal)) {
            $tipoVal = $tipoVal['nombre'] ?? $tipoVal['Nombre'] ?? '';
        }
        $tipo = strtolower(trim((string) $tipoVal));

        if ($tipo === '') {
            return true;
        }

        return str_contains($tipo, 'veterinario') || str_contains($tipo, 'tecnico') || str_contains($tipo, 'técnico');
    }

    private function filterVetTechStaff(array $personal): array
    {
        return array_values(array_filter($personal, fn (array $persona) => $this->isVetOrTech($persona)));
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
        $animalId    = $request->query('animal_id');
        $tipo        = $request->query('tipo');
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin    = $request->query('fecha_fin');

        $response    = $this->service->getList($animalId, $tipo, $fechaInicio, $fechaFin);
        $data        = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $palpaciones = (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) ? $data['data'] : $data;
        $animales    = $this->filterFemaleAnimals($this->service->getAnimales());

        return view('palpacion.index', compact('palpaciones', 'animales', 'animalId', 'tipo', 'fechaInicio', 'fechaFin'));
    }

    public function create()
    {
        $animales = $this->filterFemaleAnimals($this->service->getAnimales());
        $personal = $this->filterVetTechStaff($this->service->getPersonalFinca());
        return view('palpacion.create', compact('animales', 'personal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha'     => 'nullable|date',
            'tipo'      => 'nullable|string|max:11',
            'animal_id' => 'required|integer',
            'etapa_id'  => 'required|integer',
        ], [
            'animal_id.required' => 'El animal es requerido.',
            'etapa_id.required'  => 'La etapa del animal es requerida.',
        ]);

        $data = [
            'personal_finca_id' => $request->input('tecnico_id') !== '' ? $request->input('tecnico_id') : null,
            'tipo'              => $request->input('tipo'),
            'fecha'             => $request->input('fecha'),
            'animal_id'         => $request->input('animal_id'),
            'etapa_id'          => $request->input('etapa_id'),
        ];

        $response = $this->service->create($data);

        if ($response['success'] ?? false) {
            return redirect()->route('palpacion.index')->with('success', 'Palpación registrada exitosamente.');
        }
        return back()->withInput()->with('error', $this->apiMessage($response, 'Error al crear el registro.'));
    }

    public function show(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('palpacion.index')->with('error', 'Registro no encontrado.');
        }
        $palpacion = $response['data'];
        return view('palpacion.show', compact('palpacion'));
    }

    public function edit(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('palpacion.index')->with('error', 'Registro no encontrado.');
        }
        $palpacion = $response['data'];
        $animales  = $this->filterFemaleAnimals($this->service->getAnimales());
        $personal  = $this->filterVetTechStaff($this->service->getPersonalFinca());
        return view('palpacion.edit', compact('palpacion', 'animales', 'personal'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'tipo'  => 'nullable|string|max:11',
            'fecha' => 'nullable|date',
        ]);

        $data = [
            'personal_finca_id' => $request->input('tecnico_id') !== '' ? $request->input('tecnico_id') : null,
            'tipo'              => $request->input('tipo'),
            'fecha'             => $request->input('fecha'),
        ];

        $response = $this->service->update($id, $data);
        if ($response['success'] ?? false) {
            return redirect()->route('palpacion.index')->with('success', 'Palpación actualizada exitosamente.');
        }
        return back()->withInput()->with('error', $this->apiMessage($response, 'Error al actualizar.'));
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('palpacion.index')->with('success', 'Palpación eliminada.');
        }
        return redirect()->route('palpacion.index')->with('error', $this->apiMessage($response, 'Error al eliminar.'));
    }
}
