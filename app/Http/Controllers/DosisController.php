<?php

namespace App\Http\Controllers;

use App\Services\Contracts\DosisServiceInterface;
use Illuminate\Http\Request;

class DosisController extends Controller
{
    public function __construct(protected DosisServiceInterface $service) {}

    public function index(Request $request)
    {
        $vacunaId = $request->query('vacuna_id');
        $vigentes = $request->boolean('vigentes');

        $response = $this->service->getList($vacunaId ? (int) $vacunaId : null, $vigentes ?: null);
        $dosis = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $vacunas = $this->service->getVacunas();

        return view('dosis.index', compact('dosis', 'vacunas', 'vacunaId', 'vigentes'));
    }

    public function create()
    {
        $vacunas = $this->service->getVacunas();
        $casas = $this->service->getCasasComerciales();
        $animales = $this->service->getAnimales();

        return view('dosis.create', compact('vacunas', 'casas', 'animales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dosis_vacuna_id' => 'required|integer',
            'dosis_casa_id' => 'required|integer',
            'dosis_frecuencia' => 'required|integer|min:1',
            'dosis_costo' => 'nullable|numeric|min:0',
            'dosis_costo_frasco' => 'nullable|numeric|min:0',
            'dosis_fecha_uso_ini' => 'required|date',
            'dosis_fecha_uso_fin' => 'nullable|date|after:dosis_fecha_uso_ini',
            'dosis_etapa_animal_anid' => 'required|integer',
            'dosis_etapa_animal_etid' => 'required|integer',
        ]);

        $response = $this->service->create($request->only([
            'dosis_vacuna_id',
            'dosis_casa_id',
            'dosis_frecuencia',
            'dosis_costo',
            'dosis_costo_frasco',
            'dosis_fecha_uso_ini',
            'dosis_fecha_uso_fin',
            'dosis_etapa_animal_anid',
            'dosis_etapa_animal_etid',
        ]));

        if ($response['success'] ?? false) {
            return redirect()->route('dosis.index')->with('success', 'Dosis registrada exitosamente.');
        }

        return back()->withInput()->with('error', $response['message'] ?? 'Error al registrar la dosis.');
    }

    public function show(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('dosis.index')->with('error', 'Dosis no encontrada.');
        }

        $dosis = $response['data'];
        return view('dosis.show', compact('dosis'));
    }

    public function edit(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('dosis.index')->with('error', 'Dosis no encontrada.');
        }

        $dosis = $response['data'];
        return view('dosis.edit', compact('dosis'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'dosis_frecuencia' => 'required|integer|min:1',
            'dosis_costo' => 'nullable|numeric|min:0',
            'dosis_costo_frasco' => 'nullable|numeric|min:0',
            'dosis_fecha_uso_ini' => 'required|date',
            'dosis_fecha_uso_fin' => 'nullable|date|after:dosis_fecha_uso_ini',
        ]);

        $response = $this->service->update($id, $request->only([
            'dosis_frecuencia',
            'dosis_costo',
            'dosis_costo_frasco',
            'dosis_fecha_uso_ini',
            'dosis_fecha_uso_fin',
        ]));

        if ($response['success'] ?? false) {
            return redirect()->route('dosis.index')->with('success', 'Dosis actualizada exitosamente.');
        }

        return back()->withInput()->with('error', $response['message'] ?? 'Error al actualizar la dosis.');
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('dosis.index')->with('success', 'Dosis eliminada.');
        }

        return redirect()->route('dosis.index')->with('error', $response['message'] ?? 'Error al eliminar la dosis.');
    }
}