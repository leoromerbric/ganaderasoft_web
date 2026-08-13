<?php

namespace App\Http\Controllers;

use App\Services\Contracts\VacunaServiceInterface;
use Illuminate\Http\Request;

class VacunaController extends Controller
{
    public function __construct(protected VacunaServiceInterface $service) {}

    public function index(Request $request)
    {
        $nombre   = $request->query('nombre');
        $response = $this->service->getList($nombre);
        $data     = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $vacunas  = (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) ? $data['data'] : $data;

        return view('vacuna.index', compact('vacunas', 'nombre'));
    }

    public function create()
    {
        return view('vacuna.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:80',
            'descripcion' => 'nullable|string',
            'activa' => 'nullable|boolean',
        ]);

        $response = $this->service->create($request->only(['nombre', 'descripcion', 'activa']));

        if ($response['success'] ?? false) {
            return redirect()->route('vacuna.index')->with('success', 'Vacuna registrada exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al crear.');
    }

    public function show(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('vacuna.index')->with('error', 'Vacuna no encontrada.');
        }
        $vacuna = $response['data'];
        return view('vacuna.show', compact('vacuna'));
    }

    public function edit(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('vacuna.index')->with('error', 'Vacuna no encontrada.');
        }
        $vacuna = $response['data'];
        return view('vacuna.edit', compact('vacuna'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:80',
            'descripcion' => 'nullable|string',
            'activa' => 'nullable|boolean',
        ]);

        $response = $this->service->update($id, $request->only(['nombre', 'descripcion', 'activa']));

        if ($response['success'] ?? false) {
            return redirect()->route('vacuna.index')->with('success', 'Vacuna actualizada exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al actualizar.');
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('vacuna.index')->with('success', 'Vacuna eliminada.');
        }
        return redirect()->route('vacuna.index')->with('error', $response['message'] ?? 'Error al eliminar.');
    }
}
