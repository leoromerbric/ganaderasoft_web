<?php

namespace App\Http\Controllers;

use App\Services\Contracts\CasaComercialServiceInterface;
use Illuminate\Http\Request;

class CasaComercialController extends Controller
{
    protected CasaComercialServiceInterface $service;
    private string $slug = 'casas-comerciales';
    private string $name = 'Casas comerciales';
    private string $description = 'Laboratorios y proveedores de productos';

    public function __construct(CasaComercialServiceInterface $service)
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
            'laboratorio'     => 'required|string|max:60',
            'marca_comercial' => 'required|string|max:60',
            'activa'          => 'nullable|boolean',
        ], [
            'laboratorio.required'     => 'El laboratorio es requerido.',
            'marca_comercial.required' => 'La marca comercial es requerida.',
        ]);

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
            'laboratorio'     => 'required|string|max:60',
            'marca_comercial' => 'required|string|max:60',
            'activa'          => 'nullable|boolean',
        ], [
            'laboratorio.required'     => 'El laboratorio es requerido.',
            'marca_comercial.required' => 'La marca comercial es requerida.',
        ]);

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
