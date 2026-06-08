<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ServicioAnimalServiceInterface;
use Illuminate\Http\Request;

class ServicioAnimalController extends Controller
{
    public function __construct(protected ServicioAnimalServiceInterface $service) {}

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
        $tipo = strtolower(trim((string) (data_get($persona, 'Tipo_Trabajador') ?? data_get($persona, 'tipo_trabajador') ?? data_get($persona, 'personal.Tipo_Trabajador') ?? '')));

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

        $response  = $this->service->getList($animalId, $tipo, $fechaInicio, $fechaFin);
        $servicios = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $animales  = $this->filterFemaleAnimals($this->service->getAnimales());

        return view('servicio-animal.index', compact('servicios', 'animales', 'animalId', 'tipo', 'fechaInicio', 'fechaFin'));
    }

    public function create()
    {
        $animales    = $this->filterFemaleAnimals($this->service->getAnimales());
        $semenToros  = $this->service->getSemenToros();
        $personal    = $this->filterVetTechStaff($this->service->getPersonalFinca());
        $registrosCelo = $this->service->getRegistrosCelo();
        return view('servicio-animal.create', compact('animales', 'semenToros', 'personal', 'registrosCelo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'servicio_id_Animal'   => 'required|integer',
            'servicio_tipo'        => 'nullable|string|max:11',
            'servicio_fecha'       => 'nullable|date',
            'servicio_observacion' => 'nullable|string|max:100',
        ], [
            'servicio_id_Animal.required' => 'El animal es requerido.',
        ]);

        $data = $request->only([
            'servicio_id_Animal', 'servicio_semen_id', 'servicio_id_Tecnico',
            'servicio_tipo', 'servicio_fecha', 'servicio_observacion', 'servicio_celo_id',
        ]);
        // Convert empty strings to null for nullable foreign keys
        foreach (['servicio_semen_id', 'servicio_id_Tecnico', 'servicio_celo_id'] as $k) {
            if (isset($data[$k]) && $data[$k] === '') $data[$k] = null;
        }

        $response = $this->service->create($data);

        if ($response['success'] ?? false) {
            return redirect()->route('servicio-animal.index')->with('success', 'Servicio animal registrado exitosamente.');
        }
        return back()->withInput()->with('error', $this->apiMessage($response, 'Error al crear el registro.'));
    }

    public function show(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('servicio-animal.index')->with('error', 'Registro no encontrado.');
        }
        $servicio = $response['data'];
        return view('servicio-animal.show', compact('servicio'));
    }

    public function edit(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('servicio-animal.index')->with('error', 'Registro no encontrado.');
        }
        $servicio   = $response['data'];
        $semenToros = $this->service->getSemenToros();
        $personal   = $this->filterVetTechStaff($this->service->getPersonalFinca());
        $registrosCelo = $this->service->getRegistrosCelo();
        return view('servicio-animal.edit', compact('servicio', 'semenToros', 'personal', 'registrosCelo'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'servicio_tipo'        => 'nullable|string|max:11',
            'servicio_fecha'       => 'nullable|date',
            'servicio_observacion' => 'nullable|string|max:100',
        ]);

        $data = $request->only(['servicio_semen_id', 'servicio_id_Tecnico', 'servicio_tipo', 'servicio_fecha', 'servicio_observacion', 'servicio_celo_id']);
        foreach (['servicio_semen_id', 'servicio_id_Tecnico', 'servicio_celo_id'] as $k) {
            if (isset($data[$k]) && $data[$k] === '') $data[$k] = null;
        }

        $response = $this->service->update($id, $data);
        if ($response['success'] ?? false) {
            return redirect()->route('servicio-animal.index')->with('success', 'Servicio animal actualizado exitosamente.');
        }
        return back()->withInput()->with('error', $this->apiMessage($response, 'Error al actualizar.'));
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('servicio-animal.index')->with('success', 'Servicio animal eliminado.');
        }
        return redirect()->route('servicio-animal.index')->with('error', $this->apiMessage($response, 'Error al eliminar.'));
    }
}
