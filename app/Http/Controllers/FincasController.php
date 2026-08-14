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
    public function index(Request $request)
    {
        $nombre     = $request->query('nombre', '');
        $tipoFiltro = $request->query('tipo', '');

        $response = $this->fincasService->getFincas();
        
        $fincas = [];
        if (isset($response['success']) && $response['success']) {
            $fincas = $response['data']['data'] ?? $response['data'] ?? [];
        }

        // Tipos únicos para el filtro (V2 explotacion_tipo)
        $tiposList = array_map(fn($f) => $f['explotacion_tipo'] ?? null, $fincas);
        $tipos = array_values(array_unique(array_filter($tiposList)));
        sort($tipos);

        return view('fincas.index', compact('fincas', 'tipos', 'nombre', 'tipoFiltro'));
    }

    /**
     * Display the finca management dashboard and set as active finca in session
     */
    public function dashboard($id)
    {
        $response = $this->fincasService->getFinca((int)$id);

        if (!isset($response['success']) || !$response['success'] || empty($response['data'])) {
            return redirect()->route('fincas.index')->with('error', 'Finca no encontrada');
        }

        $finca = $response['data'];
        
        // Guardar finca activa en sesión
        session(['selected_finca' => $finca]);

        return view('fincas.dashboard', compact('finca'));
    }

    /**
     * Select finca into session and redirect back
     */
    public function select($id)
    {
        $response = $this->fincasService->getFinca((int)$id);

        if (isset($response['success']) && $response['success'] && !empty($response['data'])) {
            session(['selected_finca' => $response['data']]);
            $nombreFinca = $response['data']['nombre'] ?? 'Finca';
            return redirect()->back()->with('success', "Finca \"{$nombreFinca}\" seleccionada como activa");
        }

        return redirect()->route('fincas.index')->with('error', 'Finca no encontrada');
    }

    /**
     * Clear the active finca from session
     */
    public function clearSelection()
    {
        session()->forget('selected_finca');
        return redirect()->route('fincas.index')->with('success', 'Contexto de finca liberado');
    }

    /**
     * Show form to create a new finca
     */
    public function create()
    {
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
     * Store a new finca (API V2 payload format)
     */
    public function store(Request $request)
    {
        $user = session('user');
        
        if (!$user || !isset($user['id'])) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        $propietarioId = $user['propietario']['id'] ?? $user['id'];

        $data = [
            'nombre' => $request->input('nombre') ?? $request->input('Nombre'),
            'explotacion_tipo' => $request->input('explotacion_tipo') ?? $request->input('Explotacion_Tipo'),
            'propietario_id' => $propietarioId,
            'terreno' => [
                'superficie' => (float)($request->input('superficie') ?? $request->input('Superficie', 0)),
                'relieve' => $request->input('relieve') ?? $request->input('Relieve'),
                'suelo_textura' => $request->input('suelo_textura') ?? $request->input('Suelo_Textura'),
                'ph_suelo' => $request->input('ph_suelo') ?? $request->input('ph_Suelo'),
                'precipitacion' => (float)($request->input('precipitacion') ?? $request->input('Precipitacion', 0)),
                'velocidad_viento' => (float)($request->input('velocidad_viento') ?? $request->input('Velocidad_Viento', 0)),
                'temp_anual' => (string)($request->input('temp_anual') ?? $request->input('Temp_Anual', '')),
                'temp_min' => (string)($request->input('temp_min') ?? $request->input('Temp_Min', '')),
                'temp_max' => (string)($request->input('temp_max') ?? $request->input('Temp_Max', '')),
                'radiacion' => (float)($request->input('radiacion') ?? $request->input('Radiacion', 0)),
                'fuente_agua' => $request->input('fuente_agua') ?? $request->input('Fuente_Agua'),
                'caudal_disponible' => (int)($request->input('caudal_disponible') ?? $request->input('Caudal_Disponible', 0)),
                'riego_metodo' => $request->input('riego_metodo') ?? $request->input('Riego_Metodo'),
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
        $fincaResponse = $this->fincasService->getFinca((int)$id);

        if (!isset($fincaResponse['success']) || !$fincaResponse['success']) {
            return redirect()->route('fincas.index')->with('error', 'Finca no encontrada');
        }

        $finca = $fincaResponse['data'] ?? null;

        if (!$finca) {
            return redirect()->route('fincas.index')->with('error', 'Finca no encontrada');
        }

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
     * Update an existing finca (API V2 payload format)
     */
    public function update(Request $request, $id)
    {
        $user = session('user');
        
        if (!$user || !isset($user['id'])) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        $propietarioId = $user['propietario']['id'] ?? $user['id'];

        $data = [
            'nombre' => $request->input('nombre') ?? $request->input('Nombre'),
            'explotacion_tipo' => $request->input('explotacion_tipo') ?? $request->input('Explotacion_Tipo'),
            'propietario_id' => $propietarioId,
            'terreno' => [
                'superficie' => (float)($request->input('superficie') ?? $request->input('Superficie', 0)),
                'relieve' => $request->input('relieve') ?? $request->input('Relieve'),
                'suelo_textura' => $request->input('suelo_textura') ?? $request->input('Suelo_Textura'),
                'ph_suelo' => $request->input('ph_suelo') ?? $request->input('ph_Suelo'),
                'precipitacion' => (float)($request->input('precipitacion') ?? $request->input('Precipitacion', 0)),
                'velocidad_viento' => (float)($request->input('velocidad_viento') ?? $request->input('Velocidad_Viento', 0)),
                'temp_anual' => (string)($request->input('temp_anual') ?? $request->input('Temp_Anual', '')),
                'temp_min' => (string)($request->input('temp_min') ?? $request->input('Temp_Min', '')),
                'temp_max' => (string)($request->input('temp_max') ?? $request->input('Temp_Max', '')),
                'radiacion' => (float)($request->input('radiacion') ?? $request->input('Radiacion', 0)),
                'fuente_agua' => $request->input('fuente_agua') ?? $request->input('Fuente_Agua'),
                'caudal_disponible' => (int)($request->input('caudal_disponible') ?? $request->input('Caudal_Disponible', 0)),
                'riego_metodo' => $request->input('riego_metodo') ?? $request->input('Riego_Metodo'),
            ]
        ];

        $response = $this->fincasService->updateFinca((int)$id, $data);

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
