<?php

namespace App\Http\Controllers;

use App\Services\Contracts\LecheServiceInterface;
use App\Services\Contracts\LactanciaServiceInterface;
use Illuminate\Http\Request;

class LecheController extends Controller
{
    protected LecheServiceInterface $lecheService;
    protected LactanciaServiceInterface $lactanciaService;

    public function __construct(
        LecheServiceInterface $lecheService,
        LactanciaServiceInterface $lactanciaService
    ) {
        $this->lecheService = $lecheService;
        $this->lactanciaService = $lactanciaService;
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

    /**
     * Display a listing of milk production records
     */
    public function index(Request $request)
    {
        $lactanciaId = $request->query('lactancia_id');
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin = $request->query('fecha_fin');
        
        $response = $this->lecheService->getRegistrosLeche($lactanciaId, $fechaInicio, $fechaFin);
        
        if (!$response['success']) {
            return redirect()->route('dashboard')->with('error', $response['message']);
        }

        // Get lactation periods for filter dropdown
        $lactanciasResponse = $this->lactanciaService->getLactancias();
        $lactancias = $lactanciasResponse['success'] ? ($lactanciasResponse['data'] ?? []) : [];
        $lactanciaMap = collect($lactancias)
            ->filter(fn ($lactancia) => isset($lactancia['lactancia_id']))
            ->mapWithKeys(function ($lactancia) {
                $animalId = $lactancia['lactancia_etapa_anid'] ?? null;
                $animalNombre = $lactancia['animal']['Nombre'] ?? ($animalId ? ('Animal #'.$animalId) : 'Animal no disponible');
                return [(int)$lactancia['lactancia_id'] => [
                    'animal_nombre' => $animalNombre,
                    'animal_id' => $animalId,
                ]];
            })
            ->all();

        $registrosLeche = $response['data'] ?? [];
        $registrosLeche = array_map(function ($registro) use ($lactanciaMap) {
            $lactanciaId = (int)($registro['leche_lactancia_id'] ?? 0);
            $meta = $lactanciaMap[$lactanciaId] ?? null;
            $registro['animal_nombre'] = $registro['lactancia']['animal']['Nombre']
                ?? ($meta['animal_nombre'] ?? 'Animal no disponible');
            return $registro;
        }, $registrosLeche);

        return view('leche.index', compact('registrosLeche', 'lactancias', 'lactanciaId'));
    }

    /**
     * Show the form for creating a new milk production record
     */
    public function create(Request $request)
    {
        $lactanciaId = $request->query('lactancia_id');
        
        $lactanciasResponse = $this->lactanciaService->getLactancias();
        $lactancias = $lactanciasResponse['success'] ? ($lactanciasResponse['data'] ?? []) : [];

        return view('leche.create', compact('lactancias', 'lactanciaId'));
    }

    /**
     * Store a newly created milk production record
     */
    public function store(Request $request)
    {
        $request->validate([
            'leche_fecha_pesaje' => 'required|date',
            'leche_pesaje_Total' => 'required|numeric|min:0',
            'leche_lactancia_id' => 'required|integer',
        ], [
            'leche_fecha_pesaje.required' => 'La fecha de pesaje es requerida.',
            'leche_fecha_pesaje.date' => 'La fecha de pesaje debe ser una fecha válida.',
            'leche_pesaje_Total.required' => 'La cantidad de leche es requerida.',
            'leche_pesaje_Total.numeric' => 'La cantidad de leche debe ser un número.',
            'leche_pesaje_Total.min' => 'La cantidad de leche debe ser mayor a 0.',
            'leche_lactancia_id.required' => 'El período de lactancia es requerido.',
        ]);

        $data = $request->only([
            'leche_fecha_pesaje',
            'leche_pesaje_Total',
            'leche_lactancia_id'
        ]);

        $response = $this->lecheService->createRegistroLeche($data);

        if ($response['success']) {
            $lactanciaId = $request->input('leche_lactancia_id');
            return redirect()->route('leche.index', ['lactancia_id' => $lactanciaId])
                ->with('success', 'Registro de leche creado exitosamente.');
        }

        return back()->withInput()->with('error', $this->apiMessage($response, 'No se pudo crear el registro de leche.'));
    }

    /**
     * Display the specified milk production record
     */
    public function show(string $id)
    {
        $response = $this->lecheService->getRegistroLeche($id);

        if (!$response['success']) {
            return redirect()->route('leche.index')->with('error', $this->apiMessage($response, 'No se pudo cargar el registro de leche.'));
        }

        $registroLeche = $response['data'];
        
        return view('leche.show', compact('registroLeche'));
    }

    /**
     * Show the form for editing the specified milk production record
     */
    public function edit(string $id)
    {
        $response = $this->lecheService->getRegistroLeche($id);

        if (!$response['success']) {
            return redirect()->route('leche.index')->with('error', $this->apiMessage($response, 'No se pudo cargar el registro de leche.'));
        }

        $registroLeche = $response['data'];

        $lactanciasResponse = $this->lactanciaService->getLactancias();
        $lactancias = $lactanciasResponse['success'] ? ($lactanciasResponse['data'] ?? []) : [];

        return view('leche.edit', compact('registroLeche', 'lactancias'));
    }

    /**
     * Update the specified milk production record
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'leche_fecha_pesaje' => 'required|date',
            'leche_pesaje_Total' => 'required|numeric|min:0',
            'leche_lactancia_id' => 'required|integer',
        ], [
            'leche_fecha_pesaje.required' => 'La fecha de pesaje es requerida.',
            'leche_fecha_pesaje.date' => 'La fecha de pesaje debe ser una fecha válida.',
            'leche_pesaje_Total.required' => 'La cantidad de leche es requerida.',
            'leche_pesaje_Total.numeric' => 'La cantidad de leche debe ser un número.',
            'leche_pesaje_Total.min' => 'La cantidad de leche debe ser mayor a 0.',
            'leche_lactancia_id.required' => 'El período de lactancia es requerido.',
        ]);

        $data = $request->only([
            'leche_fecha_pesaje',
            'leche_pesaje_Total',
            'leche_lactancia_id'
        ]);

        $response = $this->lecheService->updateRegistroLeche($id, $data);

        if ($response['success']) {
            return redirect()->route('leche.index')
                ->with('success', 'Registro de leche actualizado exitosamente.');
        }

        return back()->withInput()->with('error', $this->apiMessage($response, 'No se pudo actualizar el registro de leche.'));
    }

    /**
     * Remove the specified milk production record
     */
    public function destroy(string $id)
    {
        $response = $this->lecheService->deleteRegistroLeche($id);

        if ($response['success']) {
            return redirect()->route('leche.index')
                ->with('success', 'Registro de leche eliminado exitosamente.');
        }

        return redirect()->route('leche.index')->with('error', $this->apiMessage($response, 'No se pudo eliminar el registro de leche.'));
    }
}