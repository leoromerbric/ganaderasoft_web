<?php

namespace App\Http\Controllers;

use App\Services\Contracts\DiaPalpacionServiceInterface;
use Illuminate\Http\Request;

class DiaPalpacionController extends Controller
{
    protected DiaPalpacionServiceInterface $service;
    private string $slug = 'dias-palpacion';
    private string $name = 'Días de palpación';
    private string $description = 'Rangos de días para diagnóstico de gestación';

    public function __construct(DiaPalpacionServiceInterface $service)
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
            'dias' => 'required|integer|min:0',
        ]);

        $result = $this->service->create($validated);
        if ($result['success']) {
            return redirect()->route("admin.{$this->slug}.index")->with('success', 'Elemento guardado exitosamente.');
        }

        return back()->withInput()->with('error', $result['message']);
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
            'dias' => 'required|integer|min:0',
        ]);

        $result = $this->service->update((int)$id, $validated);
        if ($result['success']) {
            return redirect()->route("admin.{$this->slug}.index")->with('success', 'Elemento actualizado exitosamente.');
        }

        return back()->withInput()->with('error', $result['message']);
    }

    public function destroy($id)
    {
        $result = $this->service->deleteItem((int)$id);
        if ($result['success']) {
            return redirect()->route("admin.{$this->slug}.index")->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }
}
