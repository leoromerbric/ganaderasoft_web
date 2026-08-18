<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ComposicionRazaServiceInterface;
use App\Services\Contracts\TipoAnimalServiceInterface;
use App\Services\Contracts\FincasServiceInterface;
use Illuminate\Http\Request;

class ComposicionRazaController extends Controller
{
    protected ComposicionRazaServiceInterface $service;
    protected TipoAnimalServiceInterface $tipoAnimalService;
    protected FincasServiceInterface $fincasService;

    private string $slug = 'razas';
    private string $name = 'Razas y composiciones';
    private string $description = 'Composición de razas genéticas';

    public function __construct(
        ComposicionRazaServiceInterface $service,
        TipoAnimalServiceInterface $tipoAnimalService,
        FincasServiceInterface $fincasService
    ) {
        $this->service = $service;
        $this->tipoAnimalService = $tipoAnimalService;
        $this->fincasService = $fincasService;
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
        
        return view("{$this->slug}.index", compact('catalog', 'items', 'tiposMap'));
    }

    public function create()
    {
        $catalog = ['slug' => $this->slug, 'name' => $this->name, 'description' => $this->description];
        $tiposAnimal = $this->tipoAnimalService->getAll();
        $fincasResponse = $this->fincasService->getFincas();
        $fincas = ($fincasResponse['success'] ?? false) ? ($fincasResponse['data']['data'] ?? $fincasResponse['data'] ?? []) : [];

        return view("{$this->slug}.create", compact('catalog', 'tiposAnimal', 'fincas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:30',
            'siglas' => 'nullable|string|max:6',
            'pelaje' => 'nullable|string|max:80',
            'proposito' => 'nullable|string|max:15',
            'tipo_raza' => 'nullable|string|max:12',
            'origen' => 'nullable|string|max:60',
            'caracteristica_especial' => 'nullable|string|max:80',
            'proporcion_raza' => 'nullable|string|max:7',
            'tipo_animal_id' => 'nullable|integer',
            'finca_id' => 'required|integer',
        ]);

        if (isset($validated['nombre'])) {
            $validated['nombre'] = ucfirst($validated['nombre']);
        }
        if (isset($validated['siglas'])) {
            $validated['siglas'] = strtoupper($validated['siglas']);
        }

        $result = $this->service->create($validated);
        if ($result['success']) {
            return redirect()->route("{$this->slug}.index")->with('success', 'Elemento guardado exitosamente.');
        }

        return back()->withInput()->with('error', $result['message']);
    }

    public function show($id)
    {
        $catalog = ['slug' => $this->slug, 'name' => $this->name, 'description' => $this->description];
        $result = $this->service->getById((int)$id);
        if (!$result['success']) {
            return redirect()->route("{$this->slug}.index")->with('error', $result['message']);
        }
        $item = $result['data'];

        $fincaNombre = null;
        if (!empty($item['finca_id'])) {
            $fincaRes = $this->fincasService->getFinca((int)$item['finca_id']);
            if ($fincaRes['success'] ?? false) {
                $fincaNombre = $fincaRes['data']['nombre'] ?? ($fincaRes['data']['data']['nombre'] ?? null);
            }
        }

        $tipoAnimalNombre = null;
        if (!empty($item['tipo_animal_id'])) {
            $tipoRes = $this->tipoAnimalService->getById((int)$item['tipo_animal_id']);
            if ($tipoRes['success'] ?? false) {
                $tipoAnimalNombre = $tipoRes['data']['nombre'] ?? ($tipoRes['data']['data']['nombre'] ?? null);
            }
        }

        return view("{$this->slug}.show", compact('catalog', 'item', 'fincaNombre', 'tipoAnimalNombre'));
    }

    public function edit($id)
    {
        $catalog = ['slug' => $this->slug, 'name' => $this->name, 'description' => $this->description];
        $result = $this->service->getById((int)$id);
        if (!$result['success']) {
            return redirect()->route("{$this->slug}.index")->with('error', $result['message']);
        }
        $item = $result['data'];
        
        if (empty($item['finca_id'])) {
            return redirect()->route("{$this->slug}.index")->with('error', 'No tienes permiso para editar registros públicos.');
        }
        $tiposAnimal = $this->tipoAnimalService->getAll();
        $fincasResponse = $this->fincasService->getFincas();
        $fincas = ($fincasResponse['success'] ?? false) ? ($fincasResponse['data']['data'] ?? $fincasResponse['data'] ?? []) : [];

        return view("{$this->slug}.edit", compact('catalog', 'item', 'tiposAnimal', 'fincas'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:30',
            'siglas' => 'nullable|string|max:6',
            'pelaje' => 'nullable|string|max:80',
            'proposito' => 'nullable|string|max:15',
            'tipo_raza' => 'nullable|string|max:12',
            'origen' => 'nullable|string|max:60',
            'caracteristica_especial' => 'nullable|string|max:80',
            'proporcion_raza' => 'nullable|string|max:7',
            'tipo_animal_id' => 'nullable|integer',
            'finca_id' => 'required|integer',
        ]);

        if (isset($validated['nombre'])) {
            $validated['nombre'] = ucfirst($validated['nombre']);
        }
        if (isset($validated['siglas'])) {
            $validated['siglas'] = strtoupper($validated['siglas']);
        }

        $result = $this->service->update((int)$id, $validated);
        if ($result['success']) {
            return redirect()->route("{$this->slug}.index")->with('success', 'Elemento actualizado exitosamente.');
        }

        return back()->withInput()->with('error', $result['message']);
    }

    public function destroy($id)
    {
        $result = $this->service->deleteItem((int)$id);
        if ($result['success']) {
            return redirect()->route("{$this->slug}.index")->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }
}
