<?php

namespace App\Http\Controllers;

use App\Services\Contracts\FincasServiceInterface;
use Illuminate\Http\Request;

class FincasController extends Controller
{
    protected FincasServiceInterface $fincasService;

    public function __construct(FincasServiceInterface $fincasService)
    {
        $this->fincasService = $fincasService;
    }

    /**
     * Display list of fincas
     */
    public function index()
    {
        $response = $this->fincasService->getFincas();

        if (isset($response['success']) && $response['success']) {
            $fincas = $response['data']['data'] ?? [];
            $pagination = [
                'current_page' => $response['data']['current_page'] ?? 1,
                'last_page' => $response['data']['last_page'] ?? 1,
                'total' => $response['data']['total'] ?? 0,
            ];

            return view('fincas.index', compact('fincas', 'pagination'));
        }

        return view('fincas.index', [
            'fincas' => [],
            'pagination' => [],
            'error' => $response['message'] ?? 'Error al obtener las fincas'
        ]);
    }

    /**
     * Display dashboard for a specific finca
     */
    public function dashboard($id)
    {
        $response = $this->fincasService->getFincas();

        if (isset($response['success']) && $response['success']) {
            $fincas = $response['data']['data'] ?? [];
            
            // Find the selected finca
            $finca = collect($fincas)->firstWhere('id_Finca', (int)$id);

            if ($finca) {
                // Store selected finca in session
                session(['selected_finca' => $finca]);
                
                return view('fincas.dashboard', compact('finca'));
            }

            return redirect()->route('fincas.index')->with('error', 'Finca no encontrada');
        }

        return redirect()->route('fincas.index')->with('error', $response['message'] ?? 'Error al obtener la finca');
    }

    /**
     * API endpoint to get fincas list
     */
    public function apiFincas()
    {
        $response = $this->fincasService->getFincas();

        if (isset($response['success']) && $response['success']) {
            return response()->json($response);
        }

        return response()->json([
            'success' => false,
            'message' => $response['message'] ?? 'Error al obtener las fincas'
        ], 500);
    }
}
