<?php

namespace App\Http\Controllers;

use App\Services\Contracts\MedidasCorporalesServiceInterface;
use App\Services\Contracts\AnimalesServiceInterface;
use Illuminate\Http\Request;

class MedidasCorporalesController extends Controller
{
    protected MedidasCorporalesServiceInterface $medidasCorporalesService;
    protected AnimalesServiceInterface $animalesService;

    public function __construct(
        MedidasCorporalesServiceInterface $medidasCorporalesService,
        AnimalesServiceInterface $animalesService
    ) {
        $this->medidasCorporalesService = $medidasCorporalesService;
        $this->animalesService = $animalesService;
    }

    /**
     * Display a listing of body measurements
     */
    public function index(Request $request)
    {
        $animalId = $request->query('animal_id');
        
        $response = $this->medidasCorporalesService->getMedidasCorporales($animalId);
        
        if (!$response['success']) {
            return redirect()->route('dashboard')->with('error', $response['message']);
        }

        // Get animals for filter dropdown
        $animalesResponse = $this->animalesService->getAnimales();
        $animales = $animalesResponse['success'] ? ($animalesResponse['data']['data'] ?? []) : [];

        $animalesPorId = collect($animales)->keyBy(fn ($animal) => $animal['id_Animal'] ?? null);

        $medidasCorporales = collect($response['data'] ?? [])->map(function ($medida) use ($animalesPorId) {
            $animalIdRegistro = $medida['medida_etapa_anid'] ?? $medida['animal_id'] ?? null;
            $animal = $animalesPorId->get($animalIdRegistro, []);

            $medida['animal_nombre'] = $medida['animal_nombre'] ?? ($animal['Nombre'] ?? null);
            $medida['animal_identificacion'] = $medida['animal_identificacion'] ?? ($animal['Nombre'] ?? null);

            return $medida;
        })->all();

        $alturas = collect($medidasCorporales)->pluck('Altura_HC')->filter(fn ($valor) => is_numeric($valor))->map(fn ($valor) => (float) $valor);
        $longitudes = collect($medidasCorporales)->pluck('Longitud_LC')->filter(fn ($valor) => is_numeric($valor))->map(fn ($valor) => (float) $valor);
        $perimetros = collect($medidasCorporales)->pluck('Perimetro_PT')->filter(fn ($valor) => is_numeric($valor))->map(fn ($valor) => (float) $valor);

        $estadisticas = [
            'altura_promedio' => $alturas->isNotEmpty() ? number_format($alturas->avg(), 2, ',', '.') : '0,00',
            'largura_promedio' => $longitudes->isNotEmpty() ? number_format($longitudes->avg(), 2, ',', '.') : '0,00',
            'circunferencia_promedio' => $perimetros->isNotEmpty() ? number_format($perimetros->avg(), 2, ',', '.') : '0,00',
        ];

        return view('medidas-corporales.index', compact('medidasCorporales', 'animales', 'animalId', 'estadisticas'));
    }

    /**
     * Show the form for creating a new body measurement record
     */
    public function create()
    {
        $animalesResponse = $this->animalesService->getAnimales();
        $animales = $animalesResponse['success'] ? ($animalesResponse['data']['data'] ?? []) : [];

        return view('medidas-corporales.create', compact('animales'));
    }

    /**
     * Store a newly created body measurement record
     */
    public function store(Request $request)
    {
        $request->validate([
            'medida_etapa_anid' => 'required|integer',
            'medida_etapa_etid' => 'required|integer',
            'Altura_HC' => 'nullable|numeric|min:0|max:300',
            'Altura_HG' => 'nullable|numeric|min:0|max:300',
            'Perimetro_PT' => 'nullable|numeric|min:0|max:500',
            'Perimetro_PCA' => 'nullable|numeric|min:0|max:200',
            'Longitud_LC' => 'nullable|numeric|min:0|max:500',
            'Longitud_LG' => 'nullable|numeric|min:0|max:200',
            'Anchura_AG' => 'nullable|numeric|min:0|max:200',
        ], [
            'medida_etapa_anid.required' => 'El animal es requerido.',
            'Altura_HC.numeric' => 'La altura a la cruz debe ser un número.',
            'Altura_HC.max' => 'La altura a la cruz no puede exceder 300 cm.',
            'Altura_HG.numeric' => 'La altura a la grupa debe ser un número.',
            'Altura_HG.max' => 'La altura a la grupa no puede exceder 300 cm.',
            'Perimetro_PT.numeric' => 'El perímetro torácico debe ser un número.',
            'Perimetro_PT.max' => 'El perímetro torácico no puede exceder 500 cm.',
            'Perimetro_PCA.numeric' => 'El perímetro de caña debe ser un número.',
            'Perimetro_PCA.max' => 'El perímetro de caña no puede exceder 200 cm.',
            'Longitud_LC.numeric' => 'La longitud corporal debe ser un número.',
            'Longitud_LC.max' => 'La longitud corporal no puede exceder 500 cm.',
            'Longitud_LG.numeric' => 'La longitud de grupa debe ser un número.',
            'Longitud_LG.max' => 'La longitud de grupa no puede exceder 200 cm.',
            'Anchura_AG.numeric' => 'La anchura de grupa debe ser un número.',
            'Anchura_AG.max' => 'La anchura de grupa no puede exceder 200 cm.',
        ]);

        $data = $request->only([
            'Altura_HC',
            'Altura_HG',
            'Perimetro_PT',
            'Perimetro_PCA',
            'Longitud_LC',
            'Longitud_LG',
            'Anchura_AG',
            'medida_etapa_anid',
            'medida_etapa_etid'
        ]);

        $response = $this->medidasCorporalesService->createMedidaCorporal($data);

        if ($response['success']) {
            return redirect()->route('medidas-corporales.index')
                ->with('success', 'Medidas corporales registradas exitosamente.');
        }

        return back()->withInput()->with('error', $response['message']);
    }

    /**
     * Display the specified body measurement record
     */
    public function show(string $id)
    {
        $response = $this->medidasCorporalesService->getMedidaCorporal($id);

        if (!$response['success']) {
            return redirect()->route('medidas-corporales.index')->with('error', $response['message']);
        }

        $medidaCorporal = $response['data'];
        
        return view('medidas-corporales.show', compact('medidaCorporal'));
    }

    /**
     * Show the form for editing the specified body measurement record
     */
    public function edit(string $id)
    {
        $response = $this->medidasCorporalesService->getMedidaCorporal($id);

        if (!$response['success']) {
            return redirect()->route('medidas-corporales.index')->with('error', $response['message']);
        }

        $medidaCorporal = $response['data'];

        $animalesResponse = $this->animalesService->getAnimales();
        $animales = $animalesResponse['success'] ? ($animalesResponse['data']['data'] ?? []) : [];

        return view('medidas-corporales.edit', compact('medidaCorporal', 'animales'));
    }

    /**
     * Update the specified body measurement record
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'medida_etapa_anid' => 'required|integer',
            'medida_etapa_etid' => 'required|integer',
            'Altura_HC' => 'nullable|numeric|min:0|max:300',
            'Altura_HG' => 'nullable|numeric|min:0|max:300',
            'Perimetro_PT' => 'nullable|numeric|min:0|max:500',
            'Perimetro_PCA' => 'nullable|numeric|min:0|max:200',
            'Longitud_LC' => 'nullable|numeric|min:0|max:500',
            'Longitud_LG' => 'nullable|numeric|min:0|max:200',
            'Anchura_AG' => 'nullable|numeric|min:0|max:200',
        ], [
            'medida_etapa_anid.required' => 'El animal es requerido.',
            'Altura_HC.numeric' => 'La altura a la cruz debe ser un número.',
            'Altura_HC.max' => 'La altura a la cruz no puede exceder 300 cm.',
            'Altura_HG.numeric' => 'La altura a la grupa debe ser un número.',
            'Altura_HG.max' => 'La altura a la grupa no puede exceder 300 cm.',
            'Perimetro_PT.numeric' => 'El perímetro torácico debe ser un número.',
            'Perimetro_PT.max' => 'El perímetro torácico no puede exceder 500 cm.',
            'Perimetro_PCA.numeric' => 'El perímetro de caña debe ser un número.',
            'Perimetro_PCA.max' => 'El perímetro de caña no puede exceder 200 cm.',
            'Longitud_LC.numeric' => 'La longitud corporal debe ser un número.',
            'Longitud_LC.max' => 'La longitud corporal no puede exceder 500 cm.',
            'Longitud_LG.numeric' => 'La longitud de grupa debe ser un número.',
            'Longitud_LG.max' => 'La longitud de grupa no puede exceder 200 cm.',
            'Anchura_AG.numeric' => 'La anchura de grupa debe ser un número.',
            'Anchura_AG.max' => 'La anchura de grupa no puede exceder 200 cm.',
        ]);

        $data = $request->only([
            'Altura_HC',
            'Altura_HG',
            'Perimetro_PT',
            'Perimetro_PCA',
            'Longitud_LC',
            'Longitud_LG',
            'Anchura_AG',
            'medida_etapa_anid',
            'medida_etapa_etid'
        ]);

        $response = $this->medidasCorporalesService->updateMedidaCorporal($id, $data);

        if ($response['success']) {
            return redirect()->route('medidas-corporales.index')
                ->with('success', 'Medidas corporales actualizadas exitosamente.');
        }

        return back()->withInput()->with('error', $response['message']);
    }

    /**
     * Remove the specified body measurement record
     */
    public function destroy(string $id)
    {
        $response = $this->medidasCorporalesService->deleteMedidaCorporal($id);

        if ($response['success']) {
            return redirect()->route('medidas-corporales.index')
                ->with('success', 'Registro de medidas eliminado exitosamente.');
        }

        return redirect()->route('medidas-corporales.index')->with('error', $response['message']);
    }
}