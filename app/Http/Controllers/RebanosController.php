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
