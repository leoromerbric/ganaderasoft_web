<?php

namespace App\Http\Controllers;

use App\Services\Contracts\TratamientoServiceInterface;
use App\Services\Contracts\FincasServiceInterface;
use App\Services\Contracts\RebanosServiceInterface;
use Illuminate\Http\Request;

class TratamientoController extends Controller
{
    public function __construct(
        protected TratamientoServiceInterface $service,
        protected FincasServiceInterface $fincasService,
        protected RebanosServiceInterface $rebanosService
    ) {}

    public function index(Request $request)
    {
        $diagnosticoId = $request->query('diagnostico_id');
        $fechaInicio   = $request->query('fecha_inicio');
        $fechaFin      = $request->query('fecha_fin');

        $response     = $this->service->getList($diagnosticoId, $fechaInicio, $fechaFin);
        $data         = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $tratamientos = (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) ? $data['data'] : $data;
        $diagnosticos = $this->service->getDiagnosticos();

        $fincasRes  = $this->fincasService->getFincas(['incluir_archivados' => true]);
        $fincas     = $fincasRes['data'] ?? [];
        $rebanosRes = $this->rebanosService->getRebanos(['incluir_archivados' => true]);
        $rebanos    = $rebanosRes['data'] ?? [];

        return view('tratamiento.index', compact('tratamientos', 'diagnosticos', 'fincas', 'rebanos', 'diagnosticoId', 'fechaInicio', 'fechaFin'));
    }

    public function create()
    {
        $diagnosticos = $this->service->getDiagnosticos();
        $fincasRes    = $this->fincasService->getFincas();
        $fincas       = $fincasRes['data'] ?? [];
        $rebanosRes   = $this->rebanosService->getRebanos();
        $rebanos      = $rebanosRes['data'] ?? [];

        return view('tratamiento.create', compact('diagnosticos', 'fincas', 'rebanos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plan'           => 'nullable|string|max:255',
            'fecha_ini'      => 'required|date',
            'fecha_fin'      => 'required|date|after_or_equal:fecha_ini',
            'diagnostico_id' => 'nullable|integer',
        ], [
            'fecha_ini.required'       => 'La fecha de inicio es requerida.',
            'fecha_fin.required'       => 'La fecha de fin es requerida.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
        ]);

        $data = $request->only(['plan', 'fecha_ini', 'fecha_fin', 'diagnostico_id']);
        if (isset($data['diagnostico_id']) && $data['diagnostico_id'] === '') {
            $data['diagnostico_id'] = null;
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
        $fincasRes    = $this->fincasService->getFincas(['incluir_archivados' => true]);
        $fincas       = $fincasRes['data']['data'] ?? $fincasRes['data'] ?? [];
        $rebanosRes   = $this->rebanosService->getRebanos(['incluir_archivados' => true]);
        $rebanos      = $rebanosRes['data']['data'] ?? $rebanosRes['data'] ?? [];

        return view('tratamiento.edit', compact('tratamiento', 'diagnosticos', 'fincas', 'rebanos'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'plan'           => 'nullable|string|max:255',
            'fecha_ini'      => 'required|date',
            'fecha_fin'      => 'required|date|after_or_equal:fecha_ini',
            'diagnostico_id' => 'nullable|integer',
        ], [
            'fecha_ini.required'       => 'La fecha de inicio es requerida.',
            'fecha_fin.required'       => 'La fecha de fin es requerida.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
        ]);

        $data = $request->only(['plan', 'fecha_ini', 'fecha_fin', 'diagnostico_id']);
        if (isset($data['diagnostico_id']) && $data['diagnostico_id'] === '') {
            $data['diagnostico_id'] = null;
        }

        $response = $this->service->update($id, $data);

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
