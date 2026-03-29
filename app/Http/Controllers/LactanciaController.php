<?php

namespace App\Http\Controllers;

use App\Services\Contracts\LactanciaServiceInterface;
use App\Services\Contracts\AnimalesServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LactanciaController extends Controller
{
    protected LactanciaServiceInterface $lactanciaService;
    protected AnimalesServiceInterface $animalesService;

    public function __construct(
        LactanciaServiceInterface $lactanciaService,
        AnimalesServiceInterface $animalesService
    ) {
        $this->lactanciaService = $lactanciaService;
        $this->animalesService = $animalesService;
    }

    /**
     * Display a listing of lactation periods
     */
    public function index(Request $request)
    {
        $animalId = $request->query('animal_id');
        $activa = $request->query('activa');
        
        $response = $this->lactanciaService->getLactancias($animalId, $activa);
        
        if (!$response['success']) {
            return redirect()->route('dashboard')->with('error', $response['message']);
        }

        // Get animals for filter dropdown
        $animalesResponse = $this->animalesService->getAnimales();
        $animales = $animalesResponse['success'] ? ($animalesResponse['data']['data'] ?? []) : [];

        $lactancias = $response['data'] ?? [];

        return view('lactancia.index', compact('lactancias', 'animales', 'animalId'));
    }

    /**
     * Show the form for creating a new lactation period
     */
    public function create()
    {
        $animalesResponse = $this->animalesService->getAnimales();
        $animales = $animalesResponse['success'] ? ($animalesResponse['data']['data'] ?? []) : [];

        return view('lactancia.create', compact('animales'));
    }

    /**
     * Store a newly created lactation period
     */
    public function store(Request $request)
    {
        $request->validate([
            'lactancia_fecha_inicio' => 'required|date',
            'lactancia_etapa_anid' => 'required|integer',
            'lactancia_etapa_etid' => 'required|integer',
            'Lactancia_fecha_fin' => 'nullable|date|after_or_equal:lactancia_fecha_inicio',
            'lactancia_secado' => 'nullable|date',
        ], [
            'lactancia_fecha_inicio.required' => 'La fecha de inicio es requerida.',
            'lactancia_fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'lactancia_etapa_anid.required' => 'El animal es requerido.',
            'lactancia_etapa_etid.required' => 'La etapa es requerida.',
            'Lactancia_fecha_fin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
            'lactancia_secado.date' => 'La fecha de secado debe ser una fecha válida.',
        ]);

        $data = $request->only([
            'lactancia_fecha_inicio',
            'Lactancia_fecha_fin',
            'lactancia_secado',
            'lactancia_etapa_anid',
            'lactancia_etapa_etid'
        ]);

        $response = $this->lactanciaService->createLactancia($data);

        if ($response['success']) {
            return redirect()->route('lactancia.index')
                ->with('success', 'Período de lactancia registrado exitosamente.');
        }

        return back()->withInput()->with('error', $response['message']);
    }

    /**
     * Display the specified lactation period
     */
    public function show(string $id)
    {
        $response = $this->lactanciaService->getLactancia($id);

        if (!$response['success']) {
            return redirect()->route('lactancia.index')->with('error', $response['message']);
        }

        $lactancia = $response['data'];
        
        return view('lactancia.show', compact('lactancia'));
    }

    /**
     * Show the form for editing the specified lactation period
     */
    public function edit(string $id)
    {
        $response = $this->lactanciaService->getLactancia($id);

        if (!$response['success']) {
            return redirect()->route('lactancia.index')->with('error', $response['message']);
        }

        $lactancia = $response['data'];

        $animalesResponse = $this->animalesService->getAnimales();
        $animales = $animalesResponse['success'] ? ($animalesResponse['data']['data'] ?? []) : [];

        return view('lactancia.edit', compact('lactancia', 'animales'));
    }

    /**
     * Update the specified lactation period
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'lactancia_fecha_inicio' => 'required|date',
            'lactancia_etapa_anid' => 'required|integer',
            'lactancia_etapa_etid' => 'required|integer',
            'Lactancia_fecha_fin' => 'nullable|date|after_or_equal:lactancia_fecha_inicio',
            'lactancia_secado' => 'nullable|date',
        ], [
            'lactancia_fecha_inicio.required' => 'La fecha de inicio es requerida.',
            'lactancia_fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'lactancia_etapa_anid.required' => 'El animal es requerido.',
            'lactancia_etapa_etid.required' => 'La etapa es requerida.',
            'Lactancia_fecha_fin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
            'lactancia_secado.date' => 'La fecha de secado debe ser una fecha válida.',
        ]);

        $data = $request->only([
            'lactancia_fecha_inicio',
            'Lactancia_fecha_fin',
            'lactancia_secado',
            'lactancia_etapa_anid',
            'lactancia_etapa_etid'
        ]);

        $response = $this->lactanciaService->updateLactancia($id, $data);

        if ($response['success']) {
            return redirect()->route('lactancia.index')
                ->with('success', 'Período de lactancia actualizado exitosamente.');
        }

        return back()->withInput()->with('error', $response['message']);
    }

    /**
     * Remove the specified lactation period
     */
    public function destroy(string $id)
    {
        $response = $this->lactanciaService->deleteLactancia($id);

        if ($response['success']) {
            return redirect()->route('lactancia.index')
                ->with('success', 'Período de lactancia eliminado exitosamente.');
        }

        return redirect()->route('lactancia.index')->with('error', $response['message']);
    }

    /**
     * Get animal's current stage for AJAX request
     */
    public function getAnimalEtapa(Request $request, $id)
    {
        try {
            Log::info('LactanciaController@getAnimalEtapa - Obteniendo etapa para animal: ' . $id);
            
            $animalResponse = $this->animalesService->getAnimal($id);
            
            if (!$animalResponse['success']) {
                Log::warning('LactanciaController@getAnimalEtapa - Error en respuesta de API', $animalResponse);
                return response()->json([
                    'success' => false,
                    'message' => 'Animal no encontrado'
                ], 404);
            }
            
            $animal = $animalResponse['data'];
            
            Log::info('LactanciaController@getAnimalEtapa - Animal obtenido', [
                'animal_id' => $id,
                'has_etapa_actual' => isset($animal['etapa_actual']),
                'etapa_actual_structure' => $animal['etapa_actual'] ?? 'null'
            ]);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'animal' => $animal,
                    'etapa_actual' => $animal['etapa_actual'] ?? null
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error en getAnimalEtapa: ' . $e->getMessage(), ['animal_id' => $id]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener etapa del animal'
            ], 500);
        }
    }
}