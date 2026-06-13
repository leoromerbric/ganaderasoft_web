<?php

namespace App\Http\Controllers;

use App\Services\Contracts\HistoricoAplicacionServiceInterface;
use Illuminate\Http\Request;

class HistoricoAplicacionController extends Controller
{
    public function __construct(protected HistoricoAplicacionServiceInterface $service) {}

    public function index(Request $request)
    {
        $vacunaId    = $request->query('vacuna_id');
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin    = $request->query('fecha_fin');

        $response   = $this->service->getList($vacunaId, $fechaInicio, $fechaFin);
        $historicos = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $vacunas    = $this->service->getVacunas();

        return view('historico-aplicacion.index', compact('historicos', 'vacunas', 'vacunaId', 'fechaInicio', 'fechaFin'));
    }

    public function create()
    {
        $vacunas = $this->service->getVacunas();
        $casas   = $this->service->getCasasComerciales();
        $dosis   = $this->service->getDosis();
        $animales = $this->service->getAnimales();
        return view('historico-aplicacion.create', compact('vacunas', 'casas', 'dosis', 'animales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ha_vacuna_id'    => 'nullable|integer|required_without:ha_dosis_id',
            'ha_casa_id'      => 'nullable|integer|required_without:ha_dosis_id',
            'ha_dosis_id'     => 'nullable|integer',
            'ha_animal_id'    => 'nullable|integer',
            'fecha_inyeccion' => 'required|date',
            'observacion'     => 'nullable|string',
        ], [
            'ha_vacuna_id.required_without' => 'La vacuna es requerida cuando no selecciona dosis.',
            'ha_casa_id.required_without' => 'La casa comercial es requerida cuando no selecciona dosis.',
            'fecha_inyeccion.required' => 'La fecha de inyección es requerida.',
        ]);

        $response = $this->service->create($request->only(['ha_vacuna_id', 'ha_casa_id', 'ha_dosis_id', 'ha_animal_id', 'fecha_inyeccion', 'observacion']));

        if ($response['success'] ?? false) {
            return redirect()->route('historico-aplicacion.index')->with('success', 'Histórico de aplicación registrado exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al crear el registro.');
    }

    public function show(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('historico-aplicacion.index')->with('error', 'Registro no encontrado.');
        }
        $historico = $response['data'];
        return view('historico-aplicacion.show', compact('historico'));
    }

    public function edit(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('historico-aplicacion.index')->with('error', 'Registro no encontrado.');
        }
        $historico = $response['data'];
        $vacunas   = $this->service->getVacunas();
        $casas     = $this->service->getCasasComerciales();
        $dosis     = $this->service->getDosis();
        $animales  = $this->service->getAnimales();
        return view('historico-aplicacion.edit', compact('historico', 'vacunas', 'casas', 'dosis', 'animales'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'fecha_inyeccion' => 'required|date',
            'observacion' => 'nullable|string',
        ], [
            'fecha_inyeccion.required' => 'La fecha de inyección es requerida.',
        ]);

        $response = $this->service->update($id, $request->only(['fecha_inyeccion', 'observacion']));

        if ($response['success'] ?? false) {
            return redirect()->route('historico-aplicacion.index')->with('success', 'Histórico de aplicación actualizado.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al actualizar.');
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('historico-aplicacion.index')->with('success', 'Registro eliminado.');
        }
        return redirect()->route('historico-aplicacion.index')->with('error', $response['message'] ?? 'Error al eliminar.');
    }
}
