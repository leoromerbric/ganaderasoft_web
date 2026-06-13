<?php

namespace App\Http\Controllers;

use App\Services\Contracts\CasaComercialServiceInterface;
use Illuminate\Http\Request;

class CasaComercialController extends Controller
{
    public function __construct(protected CasaComercialServiceInterface $service) {}

    public function index(Request $request)
    {
        $laboratorio = $request->query('laboratorio');
        $response    = $this->service->getList($laboratorio);
        $casas       = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];

        return view('casa-comercial.index', compact('casas', 'laboratorio'));
    }

    public function create()
    {
        return view('casa-comercial.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'laboratorio'     => 'required|string|max:60',
            'marca_comercial' => 'required|string|max:60',
            'activa'          => 'nullable|boolean',
        ], [
            'laboratorio.required'     => 'El laboratorio es requerido.',
            'marca_comercial.required' => 'La marca comercial es requerida.',
        ]);

        $response = $this->service->create($request->only(['laboratorio', 'marca_comercial', 'activa']));

        if ($response['success'] ?? false) {
            return redirect()->route('casa-comercial.index')->with('success', 'Casa comercial registrada exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al crear.');
    }

    public function show(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('casa-comercial.index')->with('error', 'Casa comercial no encontrada.');
        }
        $casa = $response['data'];
        return view('casa-comercial.show', compact('casa'));
    }

    public function edit(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('casa-comercial.index')->with('error', 'Casa comercial no encontrada.');
        }
        $casa = $response['data'];
        return view('casa-comercial.edit', compact('casa'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'laboratorio'     => 'required|string|max:60',
            'marca_comercial' => 'required|string|max:60',
            'activa'          => 'nullable|boolean',
        ]);

        $response = $this->service->update($id, $request->only(['laboratorio', 'marca_comercial', 'activa']));

        if ($response['success'] ?? false) {
            return redirect()->route('casa-comercial.index')->with('success', 'Casa comercial actualizada exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al actualizar.');
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('casa-comercial.index')->with('success', 'Casa comercial eliminada.');
        }
        return redirect()->route('casa-comercial.index')->with('error', $response['message'] ?? 'Error al eliminar.');
    }
}
