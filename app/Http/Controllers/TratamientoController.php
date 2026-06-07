<?php

namespace App\Http\Controllers;

use App\Services\Contracts\TratamientoServiceInterface;
use Illuminate\Http\Request;

class TratamientoController extends Controller
{
    public function __construct(protected TratamientoServiceInterface $service) {}

    public function index(Request $request)
    {
        $diagnosticoId = $request->query('diagnostico_id');
        $fechaInicio   = $request->query('fecha_inicio');
        $fechaFin      = $request->query('fecha_fin');

        $response     = $this->service->getList($diagnosticoId, $fechaInicio, $fechaFin);
        $tratamientos = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $diagnosticos = $this->service->getDiagnosticos();

        return view('tratamiento.index', compact('tratamientos', 'diagnosticos', 'diagnosticoId', 'fechaInicio', 'fechaFin'));
    }

    public function create()
    {
        $diagnosticos = $this->service->getDiagnosticos();
        return view('tratamiento.create', compact('diagnosticos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tratamiento_plan'           => 'nullable|string|max:255',
            'tratamiento_fecha_ini'      => 'required|date',
            'tratamiento_fecha_fin'      => 'required|date|after_or_equal:tratamiento_fecha_ini',
            'tratamiento_diagnostico_id' => 'nullable|integer',
        ], [
            'tratamiento_fecha_ini.required'             => 'La fecha de inicio es requerida.',
            'tratamiento_fecha_fin.required'             => 'La fecha de fin es requerida.',
            'tratamiento_fecha_fin.after_or_equal'       => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
        ]);

        $data = $request->only(['tratamiento_plan', 'tratamiento_fecha_ini', 'tratamiento_fecha_fin', 'tratamiento_diagnostico_id']);
        if (isset($data['tratamiento_diagnostico_id']) && $data['tratamiento_diagnostico_id'] === '') {
            $data['tratamiento_diagnostico_id'] = null;
        }

        $response = $this->service->create($data);

        if ($response['success'] ?? false) {
            return redirect()->route('tratamiento.index')->with('success', 'Tratamiento registrado exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al crear el registro.');
    }

    public function show(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('tratamiento.index')->with('error', 'Tratamiento no encontrado.');
        }
        $tratamiento = $response['data'];
        return view('tratamiento.show', compact('tratamiento'));
    }

    public function edit(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('tratamiento.index')->with('error', 'Tratamiento no encontrado.');
        }
        $tratamiento  = $response['data'];
        $diagnosticos = $this->service->getDiagnosticos();
        return view('tratamiento.edit', compact('tratamiento', 'diagnosticos'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'tratamiento_plan'      => 'nullable|string|max:255',
            'tratamiento_fecha_ini' => 'required|date',
            'tratamiento_fecha_fin' => 'required|date',
        ], [
            'tratamiento_fecha_ini.required' => 'La fecha de inicio es requerida.',
            'tratamiento_fecha_fin.required' => 'La fecha de fin es requerida.',
        ]);

        $response = $this->service->update($id, $request->only(['tratamiento_plan', 'tratamiento_fecha_ini', 'tratamiento_fecha_fin']));

        if ($response['success'] ?? false) {
            return redirect()->route('tratamiento.index')->with('success', 'Tratamiento actualizado exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al actualizar.');
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('tratamiento.index')->with('success', 'Tratamiento eliminado.');
        }
        return redirect()->route('tratamiento.index')->with('error', $response['message'] ?? 'Error al eliminar.');
    }
}
