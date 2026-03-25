<?php

namespace App\Http\Controllers;

use App\Services\Contracts\PesoCorporalServiceInterface;
use App\Services\Contracts\AnimalesServiceInterface;
use Illuminate\Http\Request;

class PesoCorporalController extends Controller
{
    protected PesoCorporalServiceInterface $pesoCorporalService;
    protected AnimalesServiceInterface $animalesService;

    public function __construct(
        PesoCorporalServiceInterface $pesoCorporalService,
        AnimalesServiceInterface $animalesService
    ) {
        $this->pesoCorporalService = $pesoCorporalService;
        $this->animalesService = $animalesService;
    }

    /**
     * Display a listing of weight records
     */
    public function index(Request $request)
    {
        $animalId = $request->query('animal_id');
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin = $request->query('fecha_fin');
        
        $response = $this->pesoCorporalService->getPesosCorporales($animalId, $fechaInicio, $fechaFin);
        
        if (!$response['success']) {
            return redirect()->route('dashboard')->with('error', $response['message']);
        }

        // Get animals for filter dropdown
        $animalesResponse = $this->animalesService->getAnimales();
        $animales = $animalesResponse['success'] ? ($animalesResponse['data']['data'] ?? []) : [];

        $pesosCorporales = $response['data'] ?? [];

        return view('peso-corporal.index', compact('pesosCorporales', 'animales', 'animalId', 'fechaInicio', 'fechaFin'));
    }

    /**
     * Show the form for creating a new weight record
     */
    public function create()
    {
        $animalesResponse = $this->animalesService->getAnimales();
        $animales = $animalesResponse['success'] ? ($animalesResponse['data']['data'] ?? []) : [];

        return view('peso-corporal.create', compact('animales'));
    }

    /**
     * Store a newly created weight record
     */
    public function store(Request $request)
    {
        $request->validate([
            'Fecha_Peso' => 'required|date',
            'Peso' => 'required|numeric|min:1|max:9999',
            'peso_etapa_anid' => 'required|integer',
            'Comentario' => 'nullable|string|max:255',
        ], [
            'Fecha_Peso.required' => 'La fecha de pesaje es requerida.',
            'Fecha_Peso.date' => 'La fecha de pesaje debe ser una fecha válida.',
            'Peso.required' => 'El peso es requerido.',
            'Peso.numeric' => 'El peso debe ser un número.',
            'Peso.min' => 'El peso debe ser mayor a 0.',
            'Peso.max' => 'El peso no puede exceder 9999 kg.',
            'peso_etapa_anid.required' => 'El animal es requerido.',
            'Comentario.max' => 'El comentario no puede exceder 255 caracteres.',
        ]);

        $data = $request->only([
            'Fecha_Peso',
            'Peso',
            'Comentario',
            'peso_etapa_anid'
        ]);
        
        // Default stage ID
        $data['peso_etapa_etid'] = 15;

        $response = $this->pesoCorporalService->createPesoCorporal($data);

        if ($response['success']) {
            return redirect()->route('peso-corporal.index')
                ->with('success', 'Registro de peso creado exitosamente.');
        }

        return back()->withInput()->with('error', $response['message']);
    }

    /**
     * Display the specified weight record
     */
    public function show(string $id)
    {
        $response = $this->pesoCorporalService->getPesoCorporal($id);

        if (!$response['success']) {
            return redirect()->route('peso-corporal.index')->with('error', $response['message']);
        }

        $pesoCorporal = $response['data'];
        
        return view('peso-corporal.show', compact('pesoCorporal'));
    }

    /**
     * Show the form for editing the specified weight record
     */
    public function edit(string $id)
    {
        $response = $this->pesoCorporalService->getPesoCorporal($id);

        if (!$response['success']) {
            return redirect()->route('peso-corporal.index')->with('error', $response['message']);
        }

        $pesoCorporal = $response['data'];

        $animalesResponse = $this->animalesService->getAnimales();
        $animales = $animalesResponse['success'] ? ($animalesResponse['data']['data'] ?? []) : [];

        return view('peso-corporal.edit', compact('pesoCorporal', 'animales'));
    }

    /**
     * Update the specified weight record
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'Fecha_Peso' => 'required|date',
            'Peso' => 'required|numeric|min:1|max:9999',
            'peso_etapa_anid' => 'required|integer',
            'Comentario' => 'nullable|string|max:255',
        ], [
            'Fecha_Peso.required' => 'La fecha de pesaje es requerida.',
            'Fecha_Peso.date' => 'La fecha de pesaje debe ser una fecha válida.',
            'Peso.required' => 'El peso es requerido.',
            'Peso.numeric' => 'El peso debe ser un número.',
            'Peso.min' => 'El peso debe ser mayor a 0.',
            'Peso.max' => 'El peso no puede exceder 9999 kg.',
            'peso_etapa_anid.required' => 'El animal es requerido.',
            'Comentario.max' => 'El comentario no puede exceder 255 caracteres.',
        ]);

        $data = $request->only([
            'Fecha_Peso',
            'Peso',
            'Comentario',
            'peso_etapa_anid'
        ]);
        
        // Default stage ID
        $data['peso_etapa_etid'] = 15;

        $response = $this->pesoCorporalService->updatePesoCorporal($id, $data);

        if ($response['success']) {
            return redirect()->route('peso-corporal.index')
                ->with('success', 'Registro de peso actualizado exitosamente.');
        }

        return back()->withInput()->with('error', $response['message']);
    }

    /**
     * Remove the specified weight record
     */
    public function destroy(string $id)
    {
        $response = $this->pesoCorporalService->deletePesoCorporal($id);

        if ($response['success']) {
            return redirect()->route('peso-corporal.index')
                ->with('success', 'Registro de peso eliminado exitosamente.');
        }

        return redirect()->route('peso-corporal.index')->with('error', $response['message']);
    }
}