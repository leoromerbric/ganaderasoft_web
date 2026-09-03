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
        
        $rawArchivado = $request->query('archivado');
        if ($rawArchivado !== null) {
            $norm = strtolower(trim((string)$rawArchivado));
            $archivado = in_array($norm, ['true', '1', 'archivados'], true) ? 'true' : 'false';
        } else {
            $archivado = 'false';
        }

        // Cargar todas las fincas (activas y archivadas) para permitir filtrado reactivo e instantáneo en la vista
        $response = $this->fincasService->getFincas(['incluir_archivados' => true]);
        
        $fincas = [];
        if (isset($response['success']) && $response['success']) {
            $fincas = $response['data']['data'] ?? $response['data'] ?? [];
        }

        // Tipos únicos para el filtro (V2 explotacion_tipo)
        $tiposList = array_map(fn($f) => $f['explotacion_tipo'] ?? null, $fincas);
        $tipos = array_values(array_unique(array_filter($tiposList)));
        sort($tipos);

        return view('fincas.index', compact('fincas', 'tipos', 'nombre', 'tipoFiltro', 'archivado'));
    }

    /**
     * Display details of a specific finca
     */
    public function show($id)
    {
        $response = $this->fincasService->getFinca((int)$id);

        if (!isset($response['success']) || !$response['success'] || empty($response['data'])) {
            return redirect()->route('fincas.index')->with('error', 'Finca no encontrada');
        }

        $finca = $response['data'];

        return view('fincas.show', compact('finca'));
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

    /**
     * Muestra la vista de formulario para importar fincas masivamente vía CSV/TXT.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function importarForm(Request $request)
    {
        $user = session('user');
        $propietarioId = $user['propietario']['id'] ?? $user['id'] ?? null;

        return view('fincas.importar', compact('propietarioId'));
    }

    /**
     * Procesa la importación masiva de fincas.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importar(Request $request)
    {
        $request->validate([
            'archivo'        => 'required|file|max:10240',
            'propietario_id' => 'nullable|integer',
        ], [
            'archivo.required' => 'Debe seleccionar un archivo .csv o .txt para procesar.',
            'archivo.file'     => 'El elemento subido no es un archivo válido.',
            'archivo.max'      => 'El tamaño del archivo no debe exceder los 10MB.',
        ]);

        $user = session('user');
        $propietarioId = $request->input('propietario_id') ?: ($user['propietario']['id'] ?? null);

        $response = $this->fincasService->importarFincas(
            $request->file('archivo'),
            $propietarioId ? (int)$propietarioId : null
        );

        if ($response['success'] ?? false) {
            return redirect()->route('fincas.index')
                ->with('success', $response['message'] ?? 'Fincas importadas exitosamente.');
        }

        $errorMessage = $response['message'] ?? 'Ocurrió un error al procesar el archivo.';
        $importErrors = $response['errors']['import_errors'] ?? ($response['errors'] ?? []);

        return redirect()->back()
            ->withInput()
            ->with('error', $errorMessage)
            ->with('import_errors', (array)$importErrors);
    }

    /**
     * Genera y descarga un archivo CSV de ejemplo con encabezados y filas de muestra.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function descargarPlantilla(Request $request)
    {
        $delimitador = $request->query('delimitador') === 'punto_coma' ? ';' : ',';
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="plantilla_fincas.csv"',
        ];

        $columns = ['nombre', 'explotacion_tipo', 'identificador_hierro', 'superficie', 'relieve', 'fuente_agua'];
        $samples = [
            ['Hacienda Santa Ines', 'Mixto', 'HSI-01', '150.5', 'Plano', 'Rio'],
            ['Finca El Porvenir', 'Intensiva', 'FEP-02', '85.0', 'Ondulado', 'Pozo'],
            ['Agropecuaria San Jose', 'Extensiva', '', '220.0', 'Plano', 'Quebrada'],
        ];

        $callback = function () use ($columns, $samples, $delimitador) {
            $file = fopen('php://output', 'w');
            // Inyectar BOM UTF-8 para compatibilidad transparente con Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columns, $delimitador);
            foreach ($samples as $sample) {
                fputcsv($file, $sample, $delimitador);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Archiva una finca activa.
     */
    public function archive($id)
    {
        $response = $this->fincasService->archiveFinca((int)$id);

        if ($response['success'] ?? false) {
            return redirect()->back()->with('success', $response['message'] ?? 'Finca archivada exitosamente.');
        }

        return redirect()->back()->with('error', $response['message'] ?? 'Error al archivar la finca.');
    }

    /**
     * Desarchiva una finca archivada.
     */
    public function unarchive($id)
    {
        $response = $this->fincasService->unarchiveFinca((int)$id);

        if ($response['success'] ?? false) {
            return redirect()->back()->with('success', $response['message'] ?? 'Finca desarchivada exitosamente.');
        }

        return redirect()->back()->with('error', $response['message'] ?? 'Error al desarchivar la finca.');
    }

    /**
     * Elimina definitivamente una finca y sus dependencias en cascada.
     */
    public function destroy($id)
    {
        $response = $this->fincasService->deleteFinca((int)$id);

        if ($response['success'] ?? false) {
            return redirect()->route('fincas.index')->with('success', $response['message'] ?? 'Finca eliminada definitivamente.');
        }

        return redirect()->back()->with('error', $response['message'] ?? 'Error al eliminar la finca.');
    }
}

