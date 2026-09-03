<?php

namespace App\Http\Controllers;

use App\Services\Contracts\EtapaServiceInterface;
use App\Services\Contracts\TipoAnimalServiceInterface;
use Illuminate\Http\Request;

class EtapaController extends Controller
{
    protected EtapaServiceInterface $service;
    protected TipoAnimalServiceInterface $tipoAnimalService;

    private string $slug = 'etapas';
    private string $name = 'Etapas de vida';
    private string $description = 'Etapas de vida y crecimiento del ganado';

    public function __construct(
        EtapaServiceInterface $service,
        TipoAnimalServiceInterface $tipoAnimalService
    ) {
        $this->service = $service;
        $this->tipoAnimalService = $tipoAnimalService;
    }

    public function index()
    {
        $catalog = ['slug' => $this->slug, 'name' => $this->name, 'description' => $this->description];
        $items = $this->service->getAll();
        $tiposAnimal = $this->tipoAnimalService->getAll();
        
        $tiposMap = [];
        if (is_array($tiposAnimal) || is_object($tiposAnimal)) {
            foreach ($tiposAnimal as $tipo) {
                $tId = is_array($tipo) ? ($tipo['id'] ?? null) : $tipo->id;
                $tNombre = is_array($tipo) ? ($tipo['nombre'] ?? null) : $tipo->nombre;
                if ($tId) {
                    $tiposMap[$tId] = $tNombre;
                }
            }
        }

        return view("admin.parametros.{$this->slug}.index", compact('catalog', 'items', 'tiposMap'));
    }

    public function create()
    {
        $catalog = ['slug' => $this->slug, 'name' => $this->name, 'description' => $this->description];
        $tiposAnimal = $this->tipoAnimalService->getAll();

        return view("admin.parametros.{$this->slug}.create", compact('catalog', 'tiposAnimal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:40',
            'edad_ini' => 'nullable|integer|min:0',
            'edad_fin' => 'nullable|integer|min:0',
            'sexo' => 'nullable|string|in:M,H',
            'tipo_animal_id' => 'required|integer',
        ]);

        if (isset($validated['nombre'])) {
            $validated['nombre'] = ucfirst($validated['nombre']);
        }

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
        $tiposAnimal = $this->tipoAnimalService->getAll();

        return view("admin.parametros.{$this->slug}.edit", compact('catalog', 'item', 'tiposAnimal'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:40',
            'edad_ini' => 'nullable|integer|min:0',
            'edad_fin' => 'nullable|integer|min:0',
            'sexo' => 'nullable|string|in:M,H',
            'tipo_animal_id' => 'required|integer',
        ]);

        if (isset($validated['nombre'])) {
            $validated['nombre'] = ucfirst($validated['nombre']);
        }

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
