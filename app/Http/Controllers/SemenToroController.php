<?php

namespace App\Http\Controllers;

use App\Services\Contracts\SemenToroServiceInterface;
use Illuminate\Http\Request;

class SemenToroController extends Controller
{
    public function __construct(protected SemenToroServiceInterface $service) {}

    public function index(Request $request)
    {
        $toroId  = $request->query('toro_id');
        $activo  = $request->query('activo');

        $response = $this->service->getList($toroId, $activo !== null ? (bool)$activo : null);
        $semenToros = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $toros    = $this->service->getToros();

        return view('semen-toro.index', compact('semenToros', 'toros', 'toroId', 'activo'));
    }

    public function create()
    {
        $toros = $this->service->getToros();
        return view('semen-toro.create', compact('toros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'animal_id' => 'required|integer',
            'estado'    => 'nullable|boolean',
            'fecha'     => 'nullable|date',
        ], [
            'animal_id.required' => 'El toro es requerido.',
        ]);

        $data = $request->only(['animal_id', 'estado', 'fecha']);
        if (!isset($data['estado'])) $data['estado'] = false;

        $response = $this->service->create($data);

        if ($response['success'] ?? false) {
            return redirect()->route('semen-toro.index')->with('success', 'Semen de toro registrado exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al crear el registro.');
    }

    public function show(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('semen-toro.index')->with('error', 'Registro no encontrado.');
        }
        $semen = $response['data'];
        return view('semen-toro.show', compact('semen'));
    }

    public function edit(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('semen-toro.index')->with('error', 'Registro no encontrado.');
        }
        $semen = $response['data'];
        $toros = $this->service->getToros();
        return view('semen-toro.edit', compact('semen', 'toros'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'animal_id' => 'nullable|integer',
            'estado'    => 'nullable|boolean',
            'fecha'     => 'nullable|date',
        ]);

        $data = $request->only(['animal_id', 'estado', 'fecha']);

        $response = $this->service->update($id, $data);
        if ($response['success'] ?? false) {
            return redirect()->route('semen-toro.index')->with('success', 'Semen de toro actualizado exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al actualizar.');
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('semen-toro.index')->with('success', 'Registro de semen eliminado.');
        }
        return redirect()->route('semen-toro.index')->with('error', $response['message'] ?? 'Error al eliminar.');
    }
}
