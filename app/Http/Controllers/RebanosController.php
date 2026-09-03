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
        $fincaId   = $request->query('finca_id') ?? $request->query('id_finca');
        $nombre    = $request->query('nombre', '');
        $archivado = $request->query('archivado', 'activos');

        // Cargar todos los rebaños (activos y archivados) para permitir filtrado reactivo e instantáneo en la vista
        $apiFilters = ['incluir_archivados' => true];
        if ($fincaId) {
            $apiFilters['finca_id'] = $fincaId;
        }

        $response = $this->rebanosService->getRebanos($apiFilters);
        
        $allRebanos = [];
        if (isset($response['success']) && $response['success']) {
            $allRebanos = $response['data']['data'] ?? $response['data'] ?? [];
        }

        // Cargar fincas para el filtro
        $fincasResponse = $this->fincasService->getFincas(['incluir_archivados' => true]);
        $fincas = ($fincasResponse['success'] ?? false) ? ($fincasResponse['data']['data'] ?? $fincasResponse['data'] ?? []) : [];

        // Pasar todos los rebaños a la vista para permitir filtrado reactivo en vivo sin recargas
        $rebanos = $allRebanos;

        // Stats globales iniciales
        $totalAnimales = array_sum(array_map(fn($r) => (int)($r['total_animales'] ?? count($r['animales'] ?? [])), $rebanos));
        $estadisticas = [
            'total'          => count($rebanos),
            'totalAnimales'  => $totalAnimales,
        ];

        $idFinca = $fincaId;
        return view('rebanos.index', compact('rebanos', 'fincas', 'fincaId', 'idFinca', 'nombre', 'archivado', 'estadisticas'));
    }

    /**
     * Show form to create a new rebaño
     */
    public function create()
    {
        $fincasResponse = $this->fincasService->getFincas();
        $fincas = ($fincasResponse['success'] ?? false) ? ($fincasResponse['data']['data'] ?? $fincasResponse['data'] ?? []) : [];

        return view('rebanos.create', compact('fincas'));
    }

    /**
     * Store a new rebaño (API V2 payload format)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'finca_id' => 'required|integer',
        ], [
            'nombre.required' => 'El nombre del rebaño es obligatorio',
            'finca_id.required' => 'Debe seleccionar una finca para el rebaño',
        ]);

        $data = [
            'finca_id' => (int)$request->input('finca_id'),
            'nombre' => (string)$request->input('nombre'),
        ];

        $response = $this->rebanosService->createRebano($data);

        if (isset($response['success']) && $response['success']) {
            return redirect()->route('rebanos.index', ['finca_id' => $data['finca_id']])
                ->with('success', 'Rebaño creado exitosamente');
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
        // 1. Intentar obtener el rebaño directamente por su ID
        $response = $this->rebanosService->getRebano((int)$id);

        if (isset($response['success']) && $response['success'] && !empty($response['data'])) {
            $rebano = $response['data'];
            return view('rebanos.edit', compact('rebano'));
        }

        // 2. Fallback: buscar en la lista completa incluyendo archivados
        $listResponse = $this->rebanosService->getRebanos(['incluir_archivados' => true]);

        if (isset($listResponse['success']) && $listResponse['success']) {
            $allRebanos = $listResponse['data']['data'] ?? $listResponse['data'] ?? [];
            
            // Find the rebano by ID (V2 id or legacy)
            $rebano = collect($allRebanos)->first(function ($r) use ($id) {
                return ($r['id'] ?? null) == $id || ($r['id_Rebano'] ?? null) == $id || ($r['Rebano_ID'] ?? null) == $id;
            });

            if ($rebano) {
                return view('rebanos.edit', compact('rebano'));
            }
        }

        return redirect()->route('rebanos.index')->with('error', $response['message'] ?? 'Rebaño no encontrado');
    }

    /**
     * Update an existing rebaño (API V2 payload format)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        $data = [
            'nombre' => (string)$request->input('nombre'),
        ];

        $response = $this->rebanosService->updateRebano((int)$id, $data);

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

    /**
     * Archiva un rebaño activo.
     */
    public function archive($id)
    {
        $response = $this->rebanosService->archiveRebano((int)$id);

        if ($response['success'] ?? false) {
            return redirect()->back()->with('success', $response['message'] ?? 'Rebaño archivado exitosamente.');
        }

        return redirect()->back()->with('error', $response['message'] ?? 'Error al archivar el rebaño.');
    }

    /**
     * Desarchiva un rebaño archivado.
     */
    public function unarchive($id)
    {
        $response = $this->rebanosService->unarchiveRebano((int)$id);

        if ($response['success'] ?? false) {
            return redirect()->back()->with('success', $response['message'] ?? 'Rebaño desarchivado exitosamente.');
        }

        return redirect()->back()->with('error', $response['message'] ?? 'Error al desarchivar el rebaño.');
    }

    /**
     * Elimina definitivamente un rebaño y sus dependencias en cascada.
     */
    public function destroy($id)
    {
        $response = $this->rebanosService->deleteRebano((int)$id);

        if ($response['success'] ?? false) {
            return redirect()->route('rebanos.index')->with('success', $response['message'] ?? 'Rebaño eliminado definitivamente.');
        }

        return redirect()->back()->with('error', $response['message'] ?? 'Error al eliminar el rebaño.');
    }
}

