<?php

namespace App\Http\Controllers;

use App\Services\Contracts\FincasServiceInterface;
use App\Services\Contracts\ConfiguracionServiceInterface;
use Illuminate\Http\Request;

class FincasController extends Controller
{
    protected FincasServiceInterface $fincasService;
    protected ConfiguracionServiceInterface $configuracionService;

    public function __construct(
        FincasServiceInterface $fincasService,
        ConfiguracionServiceInterface $configuracionService
    ) {
        $this->fincasService = $fincasService;
        $this->configuracionService = $configuracionService;
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
     * Show form to create a new finca
     */
    public function create()
    {
        // Load all configuration options
        $fuenteAgua = $this->configuracionService->getFuenteAgua();
        $tipoExplotacion = $this->configuracionService->getTipoExplotacion();
        $tipoRelieve = $this->configuracionService->getTipoRelieve();
        $texturaSuelo = $this->configuracionService->getTexturaSuelo();
        $phSuelo = $this->configuracionService->getPhSuelo();
        $metodoRiego = $this->configuracionService->getMetodoRiego();

        return view('fincas.create', [
            'fuenteAgua' => $fuenteAgua['data'] ?? [],
            'tipoExplotacion' => $tipoExplotacion['data'] ?? [],
            'tipoRelieve' => $tipoRelieve['data'] ?? [],
            'texturaSuelo' => $texturaSuelo['data'] ?? [],
            'phSuelo' => $phSuelo['data'] ?? [],
            'metodoRiego' => $metodoRiego['data'] ?? [],
        ]);
    }

    /**
     * Store a new finca
     */
    public function store(Request $request)
    {
        // Get the current user
        $user = session('user');
        
        if (!$user || !isset($user['id'])) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        // Prepare data for API
        $data = [
            'Nombre' => $request->input('Nombre'),
            'Explotacion_Tipo' => $request->input('Explotacion_Tipo'),
            'id_Propietario' => $user['id'],
            'terreno' => [
                'Superficie' => (float)$request->input('Superficie'),
                'Relieve' => $request->input('Relieve'),
                'Suelo_Textura' => $request->input('Suelo_Textura'),
                'ph_Suelo' => $request->input('ph_Suelo'),
                'Precipitacion' => (float)$request->input('Precipitacion'),
                'Velocidad_Viento' => (float)$request->input('Velocidad_Viento'),
                'Temp_Anual' => $request->input('Temp_Anual'),
                'Temp_Min' => $request->input('Temp_Min'),
                'Temp_Max' => $request->input('Temp_Max'),
                'Radiacion' => (float)$request->input('Radiacion'),
                'Fuente_Agua' => $request->input('Fuente_Agua'),
                'Caudal_Disponible' => (int)$request->input('Caudal_Disponible'),
                'Riego_Metodo' => $request->input('Riego_Metodo'),
            ]
        ];

        $response = $this->fincasService->createFinca($data);

        if (isset($response['success']) && $response['success']) {
            return redirect()->route('fincas.index')->with('success', 'Finca creada exitosamente');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $response['message'] ?? 'Error al crear la finca');
    }

    /**
     * Show form to edit an existing finca
     */
    public function edit($id)
    {
        // Get the finca details
        $fincaResponse = $this->fincasService->getFinca($id);

        if (!isset($fincaResponse['success']) || !$fincaResponse['success']) {
            return redirect()->route('fincas.index')->with('error', 'Finca no encontrada');
        }

        $finca = $fincaResponse['data'] ?? null;

        if (!$finca) {
            return redirect()->route('fincas.index')->with('error', 'Finca no encontrada');
        }

        // Load all configuration options
        $fuenteAgua = $this->configuracionService->getFuenteAgua();
        $tipoExplotacion = $this->configuracionService->getTipoExplotacion();
        $tipoRelieve = $this->configuracionService->getTipoRelieve();
        $texturaSuelo = $this->configuracionService->getTexturaSuelo();
        $phSuelo = $this->configuracionService->getPhSuelo();
        $metodoRiego = $this->configuracionService->getMetodoRiego();

        return view('fincas.edit', [
            'finca' => $finca,
            'fuenteAgua' => $fuenteAgua['data'] ?? [],
            'tipoExplotacion' => $tipoExplotacion['data'] ?? [],
            'tipoRelieve' => $tipoRelieve['data'] ?? [],
            'texturaSuelo' => $texturaSuelo['data'] ?? [],
            'phSuelo' => $phSuelo['data'] ?? [],
            'metodoRiego' => $metodoRiego['data'] ?? [],
        ]);
    }

    /**
     * Update an existing finca
     */
    public function update(Request $request, $id)
    {
        // Get the current user
        $user = session('user');
        
        if (!$user || !isset($user['id'])) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        // Prepare data for API
        $data = [
            'Nombre' => $request->input('Nombre'),
            'Explotacion_Tipo' => $request->input('Explotacion_Tipo'),
            'id_Propietario' => $user['id'],
            'terreno' => [
                'Superficie' => (float)$request->input('Superficie'),
                'Relieve' => $request->input('Relieve'),
                'Suelo_Textura' => $request->input('Suelo_Textura'),
                'ph_Suelo' => $request->input('ph_Suelo'),
                'Precipitacion' => (float)$request->input('Precipitacion'),
                'Velocidad_Viento' => (float)$request->input('Velocidad_Viento'),
                'Temp_Anual' => $request->input('Temp_Anual'),
                'Temp_Min' => $request->input('Temp_Min'),
                'Temp_Max' => $request->input('Temp_Max'),
                'Radiacion' => (float)$request->input('Radiacion'),
                'Fuente_Agua' => $request->input('Fuente_Agua'),
                'Caudal_Disponible' => (int)$request->input('Caudal_Disponible'),
                'Riego_Metodo' => $request->input('Riego_Metodo'),
            ]
        ];

        $response = $this->fincasService->updateFinca($id, $data);

        if (isset($response['success']) && $response['success']) {
            return redirect()->route('fincas.index')->with('success', 'Finca actualizada exitosamente');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $response['message'] ?? 'Error al actualizar la finca');
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
