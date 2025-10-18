<?php

namespace App\Http\Controllers;

use App\Services\Contracts\RebanosServiceInterface;
use Illuminate\Http\Request;

class RebanosController extends Controller
{
    protected RebanosServiceInterface $rebanosService;

    public function __construct(RebanosServiceInterface $rebanosService)
    {
        $this->rebanosService = $rebanosService;
    }

    /**
     * Display list of rebaños
     */
    public function index()
    {
        // Check if there's a selected finca in session
        $selectedFinca = session('selected_finca');
        
        if (!$selectedFinca) {
            return view('rebanos.index', [
                'rebanos' => [],
                'pagination' => [],
                'error' => 'Debe seleccionar una finca primero desde el listado de fincas'
            ]);
        }

        $response = $this->rebanosService->getRebanos();

        if (isset($response['success']) && $response['success']) {
            $allRebanos = $response['data']['data'] ?? [];
            
            // Filter rebanos by selected finca
            $rebanos = array_filter($allRebanos, function($rebano) use ($selectedFinca) {
                return isset($rebano['id_Finca']) && $rebano['id_Finca'] == $selectedFinca['id_Finca'];
            });
            
            $rebanos = array_values($rebanos); // Re-index array
            
            $pagination = [
                'current_page' => 1,
                'last_page' => 1,
                'total' => count($rebanos),
            ];

            return view('rebanos.index', compact('rebanos', 'pagination'));
        }

        return view('rebanos.index', [
            'rebanos' => [],
            'pagination' => [],
            'error' => $response['message'] ?? 'Error al obtener los rebaños'
        ]);
    }

    /**
     * Show form to create a new rebaño
     */
    public function create()
    {
        $selectedFinca = session('selected_finca');
        
        if (!$selectedFinca) {
            return redirect()->route('fincas.index')->with('error', 'Debe seleccionar una finca primero');
        }

        return view('rebanos.create', compact('selectedFinca'));
    }

    /**
     * Store a new rebaño
     */
    public function store(Request $request)
    {
        $selectedFinca = session('selected_finca');
        
        if (!$selectedFinca) {
            return redirect()->route('fincas.index')->with('error', 'Debe seleccionar una finca primero');
        }

        $data = [
            'id_Finca' => $selectedFinca['id_Finca'],
            'Nombre' => $request->input('Nombre'),
        ];

        $response = $this->rebanosService->createRebano($data);

        if (isset($response['success']) && $response['success']) {
            return redirect()->route('rebanos.index')->with('success', 'Rebaño creado exitosamente');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $response['message'] ?? 'Error al crear el rebaño');
    }

    /**
     * Show form to edit an existing rebaño
     */
    public function edit($id)
    {
        $selectedFinca = session('selected_finca');
        
        if (!$selectedFinca) {
            return redirect()->route('fincas.index')->with('error', 'Debe seleccionar una finca primero');
        }

        // Get all rebanos and find the one we need
        $response = $this->rebanosService->getRebanos();

        if (isset($response['success']) && $response['success']) {
            $allRebanos = $response['data']['data'] ?? [];
            
            // Find the rebano by ID
            $rebano = collect($allRebanos)->firstWhere('id_Rebano', (int)$id);

            if ($rebano) {
                return view('rebanos.edit', compact('rebano', 'selectedFinca'));
            }

            return redirect()->route('rebanos.index')->with('error', 'Rebaño no encontrado');
        }

        return redirect()->route('rebanos.index')->with('error', $response['message'] ?? 'Error al obtener el rebaño');
    }

    /**
     * Update an existing rebaño
     */
    public function update(Request $request, $id)
    {
        $data = [
            'Nombre' => $request->input('Nombre'),
        ];

        $response = $this->rebanosService->updateRebano($id, $data);

        if (isset($response['success']) && $response['success']) {
            return redirect()->route('rebanos.index')->with('success', 'Rebaño actualizado exitosamente');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $response['message'] ?? 'Error al actualizar el rebaño');
    }

    /**
     * API endpoint to get rebaños list
     */
    public function apiRebanos()
    {
        $response = $this->rebanosService->getRebanos();

        if (isset($response['success']) && $response['success']) {
            return response()->json($response);
        }

        return response()->json([
            'success' => false,
            'message' => $response['message'] ?? 'Error al obtener los rebaños'
        ], 500);
    }
}
