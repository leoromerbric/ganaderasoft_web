<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ServicioAnimalServiceInterface;
use Illuminate\Http\Request;

class ServicioAnimalController extends Controller
{
    public function __construct(protected ServicioAnimalServiceInterface $service) {}

    public function index(Request $request)
    {
        $animalId    = $request->query('animal_id');
        $tipo        = $request->query('tipo');
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin    = $request->query('fecha_fin');

        $response  = $this->service->getList($animalId, $tipo, $fechaInicio, $fechaFin);
        $servicios = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $animales  = $this->service->getAnimales();

        return view('servicio-animal.index', compact('servicios', 'animales', 'animalId', 'tipo', 'fechaInicio', 'fechaFin'));
    }

    public function create()
    {
        $animales    = $this->service->getAnimales();
        $semenToros  = $this->service->getSemenToros();
        $personal    = $this->service->getPersonalFinca();
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
        return back()->withInput()->with('error', $response['message'] ?? 'Error al crear el registro.');
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
        $personal   = $this->service->getPersonalFinca();
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
        return back()->withInput()->with('error', $response['message'] ?? 'Error al actualizar.');
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('servicio-animal.index')->with('success', 'Servicio animal eliminado.');
        }
        return redirect()->route('servicio-animal.index')->with('error', $response['message'] ?? 'Error al eliminar.');
    }
}
