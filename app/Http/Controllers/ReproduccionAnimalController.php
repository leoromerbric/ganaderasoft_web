<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ReproduccionAnimalServiceInterface;
use Illuminate\Http\Request;

class ReproduccionAnimalController extends Controller
{
    public function __construct(protected ReproduccionAnimalServiceInterface $service) {}

    public function index(Request $request)
    {
        $animalId    = $request->query('animal_id');
        $tipo        = $request->query('tipo');
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin    = $request->query('fecha_fin');

        $response     = $this->service->getList($animalId, $tipo, $fechaInicio, $fechaFin);
        $reproducciones = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $animales     = $this->service->getAnimales();

        return view('reproduccion-animal.index', compact('reproducciones', 'animales', 'animalId', 'tipo', 'fechaInicio', 'fechaFin'));
    }

    public function create()
    {
        $animales = $this->service->getAnimales();
        return view('reproduccion-animal.create', compact('animales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'repro_fecha_reproduccion' => 'required|date',
            'repro_tipo_reproduccion'  => 'nullable|string|max:8',
            'repro_observacion'        => 'nullable|string|max:60',
            'repro_etapa_anid'         => 'required|integer',
            'repro_etapa_etid'         => 'required|integer',
        ], [
            'repro_fecha_reproduccion.required' => 'La fecha de reproducción es requerida.',
            'repro_etapa_anid.required'         => 'El animal es requerido.',
            'repro_etapa_etid.required'         => 'La etapa del animal es requerida.',
        ]);

        $response = $this->service->create($request->only([
            'repro_fecha_reproduccion', 'repro_tipo_reproduccion',
            'repro_observacion', 'repro_etapa_anid', 'repro_etapa_etid',
        ]));

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
        $animales = $this->service->getAnimales();
        return view('reproduccion-animal.edit', compact('reproduccion', 'animales'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'repro_fecha_reproduccion' => 'required|date',
            'repro_tipo_reproduccion'  => 'nullable|string|max:8',
            'repro_observacion'        => 'nullable|string|max:60',
        ], [
            'repro_fecha_reproduccion.required' => 'La fecha de reproducción es requerida.',
        ]);

        $response = $this->service->update($id, $request->only([
            'repro_fecha_reproduccion', 'repro_tipo_reproduccion', 'repro_observacion',
        ]));

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
