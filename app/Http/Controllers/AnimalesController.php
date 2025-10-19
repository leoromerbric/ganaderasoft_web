<?php

namespace App\Http\Controllers;

use App\Services\Contracts\AnimalesServiceInterface;
use App\Services\Contracts\RebanosServiceInterface;
use Illuminate\Http\Request;

class AnimalesController extends Controller
{
    protected AnimalesServiceInterface $animalesService;
    protected RebanosServiceInterface $rebanosService;

    public function __construct(
        AnimalesServiceInterface $animalesService,
        RebanosServiceInterface $rebanosService
    ) {
        $this->animalesService = $animalesService;
        $this->rebanosService = $rebanosService;
    }

    /**
     * Display a listing of animals
     */
    public function index(Request $request)
    {
        $rebanoId = $request->query('id_rebano');
        $response = $this->animalesService->getAnimales($rebanoId);

        if (!$response['success']) {
            return redirect()->route('dashboard')->with('error', $response['message']);
        }

        // Extract animals from paginated data structure
        $animales = $response['data']['data'] ?? [];
        $rebanosResponse = $this->rebanosService->getRebanos();
        $rebanos = $rebanosResponse['success'] ? ($rebanosResponse['data']['data'] ?? []) : [];

        return view('animales.index', compact('animales', 'rebanos', 'rebanoId'));
    }

    /**
     * Show the form for creating a new animal
     */
    public function create()
    {
        $rebanosResponse = $this->rebanosService->getRebanos();
        $rebanos = $rebanosResponse['success'] ? ($rebanosResponse['data']['data'] ?? []) : [];

        $razasResponse = $this->animalesService->getRazas();
        $razas = $razasResponse['success'] ? ($razasResponse['data'] ?? []) : [];

        $estadosResponse = $this->animalesService->getEstadosSalud();
        $estados = $estadosResponse['success'] ? ($estadosResponse['data'] ?? []) : [];

        $etapasResponse = $this->animalesService->getEtapas();
        $etapas = $etapasResponse['success'] ? ($etapasResponse['data'] ?? []) : [];

        return view('animales.create', compact('rebanos', 'razas', 'estados', 'etapas'));
    }

    /**
     * Store a newly created animal in storage
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_Rebano' => 'required|integer',
            'Nombre' => 'required|string|max:255',
            'codigo_animal' => 'required|string|max:50',
            'Sexo' => 'required|in:M,F',
            'fecha_nacimiento' => 'required|date',
            'Procedencia' => 'required|string|max:255',
            'fk_composicion_raza' => 'required|integer',
            'estado_inicial.estado_id' => 'required|integer',
            'estado_inicial.fecha_ini' => 'required|date',
            'etapa_inicial.etapa_id' => 'required|integer',
            'etapa_inicial.fecha_ini' => 'required|date',
        ], [
            'id_Rebano.required' => 'Debe seleccionar un rebaño',
            'Nombre.required' => 'El nombre del animal es requerido',
            'codigo_animal.required' => 'El código del animal es requerido',
            'Sexo.required' => 'El sexo del animal es requerido',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es requerida',
            'Procedencia.required' => 'La procedencia es requerida',
            'fk_composicion_raza.required' => 'Debe seleccionar una raza',
            'estado_inicial.estado_id.required' => 'Debe seleccionar un estado de salud inicial',
            'etapa_inicial.etapa_id.required' => 'Debe seleccionar una etapa inicial',
        ]);

        $response = $this->animalesService->createAnimal($validatedData);

        if (!$response['success']) {
            return redirect()->back()
                ->withInput()
                ->with('error', $response['message']);
        }

        return redirect()->route('animales.index')
            ->with('success', '¡Animal creado exitosamente!');
    }

    /**
     * Display the specified animal
     */
    public function show(int $id)
    {
        $response = $this->animalesService->getAnimal($id);

        if (!$response['success']) {
            return redirect()->route('animales.index')->with('error', $response['message']);
        }

        $animal = $response['data'] ?? null;

        return view('animales.show', compact('animal'));
    }

    /**
     * Show the form for editing the specified animal
     */
    public function edit(int $id)
    {
        $response = $this->animalesService->getAnimal($id);

        if (!$response['success']) {
            return redirect()->route('animales.index')->with('error', $response['message']);
        }

        $animal = $response['data'] ?? null;

        $rebanosResponse = $this->rebanosService->getRebanos();
        $rebanos = $rebanosResponse['success'] ? ($rebanosResponse['data']['data'] ?? []) : [];

        $razasResponse = $this->animalesService->getRazas();
        $razas = $razasResponse['success'] ? ($razasResponse['data'] ?? []) : [];

        $estadosResponse = $this->animalesService->getEstadosSalud();
        $estados = $estadosResponse['success'] ? ($estadosResponse['data'] ?? []) : [];

        $etapasResponse = $this->animalesService->getEtapas();
        $etapas = $etapasResponse['success'] ? ($etapasResponse['data'] ?? []) : [];

        return view('animales.edit', compact('animal', 'rebanos', 'razas', 'estados', 'etapas'));
    }

    /**
     * Update the specified animal in storage
     */
    public function update(Request $request, int $id)
    {
        $validatedData = $request->validate([
            'id_Rebano' => 'required|integer',
            'Nombre' => 'required|string|max:255',
            'codigo_animal' => 'required|string|max:50',
            'Sexo' => 'required|in:M,F',
            'fecha_nacimiento' => 'required|date',
            'Procedencia' => 'required|string|max:255',
            'fk_composicion_raza' => 'required|integer',
            'archivado' => 'boolean',
            'estado_inicial.estado_id' => 'required|integer',
            'estado_inicial.fecha_ini' => 'required|date',
            'etapa_inicial.etapa_id' => 'required|integer',
            'etapa_inicial.fecha_ini' => 'required|date',
        ], [
            'id_Rebano.required' => 'Debe seleccionar un rebaño',
            'Nombre.required' => 'El nombre del animal es requerido',
            'codigo_animal.required' => 'El código del animal es requerido',
            'Sexo.required' => 'El sexo del animal es requerido',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es requerida',
            'Procedencia.required' => 'La procedencia es requerida',
            'fk_composicion_raza.required' => 'Debe seleccionar una raza',
            'estado_inicial.estado_id.required' => 'Debe seleccionar un estado de salud',
            'etapa_inicial.etapa_id.required' => 'Debe seleccionar una etapa',
        ]);

        // Ensure archivado is set
        $validatedData['archivado'] = $request->has('archivado') ? true : false;

        $response = $this->animalesService->updateAnimal($id, $validatedData);

        if (!$response['success']) {
            return redirect()->back()
                ->withInput()
                ->with('error', $response['message']);
        }

        return redirect()->route('animales.index')
            ->with('success', '¡Animal actualizado exitosamente!');
    }
}
