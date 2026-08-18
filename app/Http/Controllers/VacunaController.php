<?php

namespace App\Http\Controllers;

use App\Services\Contracts\VacunaServiceInterface;
use Illuminate\Http\Request;

class VacunaController extends Controller
{
    protected VacunaServiceInterface $service;
    private string $slug = 'vacunas';
    private string $name = 'Vacunas';
    private string $description = 'Registro de vacunas sanitarias';

    public function __construct(VacunaServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $catalog = ['slug' => $this->slug, 'name' => $this->name, 'description' => $this->description];
        $items = $this->service->getAll();
        return view("admin.parametros.{$this->slug}.index", compact('catalog', 'items'));
    }

    public function create()
    {
        $catalog = ['slug' => $this->slug, 'name' => $this->name, 'description' => $this->description];
        return view("admin.parametros.{$this->slug}.create", compact('catalog'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:80',
            'descripcion' => 'nullable|string',
            'activa' => 'nullable|boolean',
        ]);

        if (isset($validated['nombre'])) {
            $validated['nombre'] = ucfirst($validated['nombre']);
        }

        $result = $this->service->create($validated);
        if ($result['success']) {
            return redirect()->route("admin.{$this->slug}.index")->with('success', 'Elemento guardado exitosamente.');
        }

        return back()->withInput()->with('error', $result['message'] ?? 'Error al guardar.');
    }

    public function edit($id)
    {
        $catalog = ['slug' => $this->slug, 'name' => $this->name, 'description' => $this->description];
        $result = $this->service->getById((int)$id);
        if (!$result['success']) {
            return redirect()->route("admin.{$this->slug}.index")->with('error', $result['message']);
        }
        $item = $result['data'];
        return view("admin.parametros.{$this->slug}.edit", compact('catalog', 'item'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:80',
            'descripcion' => 'nullable|string',
            'activa' => 'nullable|boolean',
        ]);

        if (isset($validated['nombre'])) {
            $validated['nombre'] = ucfirst($validated['nombre']);
        }

        $result = $this->service->update((int)$id, $validated);
        if ($result['success']) {
            return redirect()->route("admin.{$this->slug}.index")->with('success', 'Elemento actualizado exitosamente.');
        }

        return back()->withInput()->with('error', $result['message'] ?? 'Error al actualizar.');
    }

    public function destroy($id)
    {
        $result = $this->service->deleteItem((int)$id);
        if ($result['success']) {
            return redirect()->route("admin.{$this->slug}.index")->with('success', $result['message']);
        }

        return back()->with('error', $result['message'] ?? 'Error al eliminar.');
    }
}
