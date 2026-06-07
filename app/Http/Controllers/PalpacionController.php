<?php

namespace App\Http\Controllers;

use App\Services\Contracts\PalpacionServiceInterface;
use Illuminate\Http\Request;

class PalpacionController extends Controller
{
    public function __construct(protected PalpacionServiceInterface $service) {}

    public function index(Request $request)
    {
        $animalId    = $request->query('animal_id');
        $tipo        = $request->query('tipo');
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin    = $request->query('fecha_fin');

        $response   = $this->service->getList($animalId, $tipo, $fechaInicio, $fechaFin);
        $palpaciones = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $animales   = $this->service->getAnimales();

        return view('palpacion.index', compact('palpaciones', 'animales', 'animalId', 'tipo', 'fechaInicio', 'fechaFin'));
    }

    public function create()
    {
        $animales = $this->service->getAnimales();
        $personal = $this->service->getPersonalFinca();
        return view('palpacion.create', compact('animales', 'personal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'palpacion_fecha'      => 'nullable|date',
            'palpacion_tipo'       => 'nullable|string|max:11',
            'palpacion_etapa_anid' => 'required|integer',
            'palpacion_etapa_etid' => 'required|integer',
        ], [
            'palpacion_etapa_anid.required' => 'El animal es requerido.',
            'palpacion_etapa_etid.required' => 'La etapa del animal es requerida.',
        ]);

        $data = $request->only(['id_Tecnico', 'palpacion_tipo', 'palpacion_fecha', 'palpacion_etapa_anid', 'palpacion_etapa_etid']);
        if (isset($data['id_Tecnico']) && $data['id_Tecnico'] === '') $data['id_Tecnico'] = null;

        $response = $this->service->create($data);

        if ($response['success'] ?? false) {
            return redirect()->route('palpacion.index')->with('success', 'Palpación registrada exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al crear el registro.');
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
        $animales  = $this->service->getAnimales();
        $personal  = $this->service->getPersonalFinca();
        return view('palpacion.edit', compact('palpacion', 'animales', 'personal'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'palpacion_tipo'  => 'nullable|string|max:11',
            'palpacion_fecha' => 'nullable|date',
        ]);

        $data = $request->only(['id_Tecnico', 'palpacion_tipo', 'palpacion_fecha']);
        if (isset($data['id_Tecnico']) && $data['id_Tecnico'] === '') $data['id_Tecnico'] = null;

        $response = $this->service->update($id, $data);
        if ($response['success'] ?? false) {
            return redirect()->route('palpacion.index')->with('success', 'Palpación actualizada exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al actualizar.');
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('palpacion.index')->with('success', 'Palpación eliminada.');
        }
        return redirect()->route('palpacion.index')->with('error', $response['message'] ?? 'Error al eliminar.');
    }
}
