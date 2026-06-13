<?php

namespace App\Http\Controllers;

use App\Services\Contracts\RebanosServiceInterface;
use App\Services\Contracts\FincasServiceInterface;
use Illuminate\Http\Request;

class RebanosController extends Controller
{
    public function __construct(
        protected RebanosServiceInterface $rebanosService,
        protected FincasServiceInterface $fincasService,
    ) {}

    /**
     * Display list of rebaños
     */
    public function index(Request $request)
    {
        $idFinca = $request->query('id_finca');
        $nombre  = $request->query('nombre', '');

        $response = $this->rebanosService->getRebanos();
        $allRebanos = ($response['success'] ?? false) ? ($response['data']['data'] ?? []) : [];

        // Cargar fincas para el filtro
        $fincasResponse = $this->fincasService->getFincas();
        $fincas = ($fincasResponse['success'] ?? false) ? ($fincasResponse['data']['data'] ?? $fincasResponse['data'] ?? []) : [];

        // Filtrar por finca y nombre
        $rebanos = array_values(array_filter($allRebanos, function ($rebano) use ($idFinca, $nombre) {
            if ($idFinca && ($rebano['id_Finca'] ?? null) != $idFinca) return false;
            if ($nombre && stripos($rebano['Nombre'] ?? '', $nombre) === false) return false;
            return true;
        }));

        // Stats
        $totalAnimales = array_sum(array_map(fn($r) => count($r['animales'] ?? []), $rebanos));
        $estadisticas = [
            'total'          => count($rebanos),
            'totalAnimales'  => $totalAnimales,
        ];

        return view('rebanos.index', compact('rebanos', 'fincas', 'idFinca', 'nombre', 'estadisticas'));
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
