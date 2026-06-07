<?php

namespace App\Http\Controllers;

use App\Services\Contracts\DiagnosticoServiceInterface;
use Illuminate\Http\Request;

class DiagnosticoController extends Controller
{
    public function __construct(protected DiagnosticoServiceInterface $service) {}

    public function index(Request $request)
    {
        $animalId    = $request->query('animal_id');
        $tipo        = $request->query('tipo');
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin    = $request->query('fecha_fin');

        $response     = $this->service->getList($animalId, $tipo, $fechaInicio, $fechaFin);
        $diagnosticos = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $animales     = $this->service->getAnimales();

        return view('diagnostico.index', compact('diagnosticos', 'animales', 'animalId', 'tipo', 'fechaInicio', 'fechaFin'));
    }

    public function create()
    {
        $animales = $this->service->getAnimales();
        return view('diagnostico.create', compact('animales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'diagnostico_descripcion' => 'nullable|string',
            'diagnostico_tipo'        => 'nullable|string|max:30',
            'diagnostico_fecha'       => 'nullable|date',
            'fk_etapa_animal_anid'    => 'required|integer',
            'fk_etapa_animal_etid'    => 'required|integer',
        ], [
            'fk_etapa_animal_anid.required' => 'El animal es requerido.',
            'fk_etapa_animal_etid.required' => 'La etapa del animal es requerida.',
        ]);

        $response = $this->service->create($request->only([
            'diagnostico_descripcion', 'diagnostico_tipo', 'diagnostico_fecha',
            'fk_etapa_animal_anid', 'fk_etapa_animal_etid',
        ]));

        if ($response['success'] ?? false) {
            return redirect()->route('diagnostico.index')->with('success', 'Diagnóstico registrado exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al crear el registro.');
    }

    public function show(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('diagnostico.index')->with('error', 'Diagnóstico no encontrado.');
        }
        $diagnostico = $response['data'];
        return view('diagnostico.show', compact('diagnostico'));
    }

    public function edit(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('diagnostico.index')->with('error', 'Diagnóstico no encontrado.');
        }
        $diagnostico = $response['data'];
        $animales    = $this->service->getAnimales();
        return view('diagnostico.edit', compact('diagnostico', 'animales'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'diagnostico_descripcion' => 'nullable|string',
            'diagnostico_tipo'        => 'nullable|string|max:30',
            'diagnostico_fecha'       => 'nullable|date',
        ]);

        $response = $this->service->update($id, $request->only([
            'diagnostico_descripcion', 'diagnostico_tipo', 'diagnostico_fecha',
        ]));

        if ($response['success'] ?? false) {
            return redirect()->route('diagnostico.index')->with('success', 'Diagnóstico actualizado exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al actualizar.');
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('diagnostico.index')->with('success', 'Diagnóstico eliminado.');
        }
        return redirect()->route('diagnostico.index')->with('error', $response['message'] ?? 'Error al eliminar.');
    }
}
