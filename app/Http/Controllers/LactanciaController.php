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

    private function apiMessage(array $response, string $fallback): string
    {
        if (!empty($response['message']) && is_string($response['message'])) {
            return $response['message'];
        }

        if (!empty($response['errors']) && is_array($response['errors'])) {
            $first = collect($response['errors'])->flatten()->first();
            if (is_string($first) && $first !== '') {
                return $first;
            }
        }

        return $fallback;
    }

    private function isFemale(array $animal): bool
    {
        $sexo = strtoupper((string)($animal['Sexo'] ?? $animal['sexo'] ?? ''));
        if ($sexo !== '') {
            return in_array($sexo, ['F', 'FEMENINO', 'HEMBRA'], true);
        }

        $label = strtolower((string)($animal['sexo_label'] ?? $animal['genero'] ?? ''));
        return in_array($label, ['femenino', 'hembra'], true);
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
        $animalMap = collect($animales)
            ->filter(fn ($animal) => isset($animal['id_Animal']))
            ->mapWithKeys(fn ($animal) => [(int)$animal['id_Animal'] => ($animal['Nombre'] ?? ('Animal #'.$animal['id_Animal']))])
            ->all();

        $lactancias = $response['data'] ?? [];
        $lactancias = array_map(function ($lactancia) use ($animalMap) {
            $animalId = $lactancia['lactancia_etapa_anid'] ?? null;
            $animalNombre = $lactancia['animal']['Nombre']
                ?? ($animalId !== null ? ($animalMap[(int)$animalId] ?? ('Animal #'.$animalId)) : 'Animal no disponible');
            $lactancia['animal_nombre'] = $animalNombre;
            return $lactancia;
        }, $lactancias);

        return view('lactancia.index', compact('lactancias', 'animales', 'animalId'));
    }

    /**
     * Show the form for creating a new lactation period
     */
    public function create()
    {
        $animalesResponse = $this->animalesService->getAnimales();
        $animales = $animalesResponse['success'] ? ($animalesResponse['data']['data'] ?? []) : [];
        $animales = array_values(array_filter($animales, fn ($animal) => $this->isFemale($animal)));

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

        return back()->withInput()->with('error', $this->apiMessage($response, 'No se pudo registrar la lactancia.'));
    }

    /**
     * Display the specified lactation period
     */
    public function show(string $id)
    {
        $response = $this->lactanciaService->getLactancia($id);

        if (!$response['success']) {
            return redirect()->route('lactancia.index')->with('error', $this->apiMessage($response, 'No se pudo cargar la lactancia.'));
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
            return redirect()->route('lactancia.index')->with('error', $this->apiMessage($response, 'No se pudo cargar la lactancia.'));
        }

        $lactancia = $response['data'];

        $animalesResponse = $this->animalesService->getAnimales();
        $animales = $animalesResponse['success'] ? ($animalesResponse['data']['data'] ?? []) : [];
        $animales = array_values(array_filter($animales, fn ($animal) => $this->isFemale($animal)));

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

        return back()->withInput()->with('error', $this->apiMessage($response, 'No se pudo actualizar la lactancia.'));
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

        return redirect()->route('lactancia.index')->with('error', $this->apiMessage($response, 'No se pudo eliminar la lactancia.'));
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