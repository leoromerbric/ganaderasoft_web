<?php

namespace App\Http\Controllers;

use App\Services\Contracts\AnimalesServiceInterface;
use App\Services\Contracts\FincasServiceInterface;
use App\Services\Contracts\RebanosServiceInterface;
use Illuminate\Http\Request;

class AnimalesController extends Controller
{
    /**
     * Inyecta los servicios necesarios para el controlador de animales.
     */
    public function __construct(
        protected AnimalesServiceInterface $animalesService,
        protected RebanosServiceInterface  $rebanosService,
        protected FincasServiceInterface   $fincasService,
    ) {}

    /**
     * Muestra el listado principal de animales.
     * Carga rebaños y fincas para los filtros y aplica mapeo de datos.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $idFinca   = $request->query('finca_id')  ? (int) $request->query('finca_id')  : null;
        $idRebano  = $request->query('rebano_id') ? (int) $request->query('rebano_id') : null;
        $sexo      = $request->query('sexo', '');
        $nombre    = $request->query('nombre', '');
        $archivado = $request->query('archivado', 'activos');

        // Mapear filtro de archivado para el servicio API
        $apiFilters = [];
        if ($archivado === 'archivados') {
            $apiFilters['archivado'] = 'true';
        } elseif ($archivado === 'todos') {
            $apiFilters['archivado'] = 'todos';
        }

        // Obtener los animales aplicando rebaño y filtro de archivado
        $response = $this->animalesService->getAnimales($idRebano, $apiFilters);
        $animales = ($response['success'] ?? false) ? ($response['data']['data'] ?? $response['data'] ?? []) : [];

        // Cargar catálogos auxiliares (rebaños y fincas)
        $rebanosResponse = $this->rebanosService->getRebanos();
        $rebanos = ($rebanosResponse['success'] ?? false) ? ($rebanosResponse['data']['data'] ?? $rebanosResponse['data'] ?? []) : [];

        $fincasResponse = $this->fincasService->getFincas();
        $fincas = ($fincasResponse['success'] ?? false) ? ($fincasResponse['data']['data'] ?? $fincasResponse['data'] ?? []) : [];

        // Construir mapa de Rebaño a Finca para validaciones y filtros en Javascript (UI)
        $mapaRebanoFinca = collect($rebanos)->keyBy('id')->map(fn($r) => $r['finca_id'] ?? null)->all();

        // Calcular estadísticas básicas en memoria
        $estadisticas = [
            'total'     => count($animales),
            'machos'    => count(array_filter($animales, fn($a) => ($a['sexo'] ?? '') === 'M')),
            'hembras'   => count(array_filter($animales, fn($a) => ($a['sexo'] ?? '') === 'H')),
            'activos'   => count(array_filter($animales, fn($a) => !($a['archivado'] ?? false))),
            'archivados'=> count(array_filter($animales, fn($a) => (bool)($a['archivado'] ?? false))),
        ];

        return view('animales.index', compact(
            'animales', 'rebanos', 'fincas',
            'idFinca', 'idRebano', 'sexo', 'nombre', 'archivado',
            'mapaRebanoFinca', 'estadisticas'
        ));
    }

    /**
     * Muestra el formulario para registrar un nuevo animal.
     * Carga todos los catálogos requeridos (rebaños, razas, estados y etapas iniciales).
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $rebanosResponse = $this->rebanosService->getRebanos();
        $rebanos = ($rebanosResponse['success'] ?? false) ? ($rebanosResponse['data']['data'] ?? $rebanosResponse['data'] ?? []) : [];

        $razasResponse = $this->animalesService->getRazas();
        $razas = ($razasResponse['success'] ?? false) ? ($razasResponse['data']['data'] ?? $razasResponse['data'] ?? []) : [];

        $estadosResponse = $this->animalesService->getEstadosSalud();
        $estados = ($estadosResponse['success'] ?? false) ? ($estadosResponse['data']['data'] ?? $estadosResponse['data'] ?? []) : [];

        return view('animales.create', compact('rebanos', 'razas', 'estados'));
    }

    /**
     * Procesa la solicitud para crear un nuevo animal en el sistema.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'rebano_id' => 'required|integer',
            'nombre' => 'required|string|max:255',
            'codigo_animal' => 'required|string|max:50',
            'sexo' => 'required|in:M,H',
            'fecha_nacimiento' => 'required|date',
            'procedencia' => 'required|string|max:255',
            'composicion_raza_id' => 'required|integer',
            'estado_inicial.estado_salud_id' => 'required|integer',
        ], [
            'rebano_id.required' => 'Debe seleccionar un rebaño',
            'nombre.required' => 'El nombre del animal es requerido',
            'codigo_animal.required' => 'El código del animal es requerido',
            'sexo.required' => 'El sexo del animal es requerido',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es requerida',
            'procedencia.required' => 'La procedencia es requerida',
            'composicion_raza_id.required' => 'Debe seleccionar una raza',
            'estado_inicial.estado_salud_id.required' => 'Debe seleccionar un estado de salud inicial',
        ]);

        $payload = [
            'rebano_id' => (int) $validatedData['rebano_id'],
            'nombre' => $validatedData['nombre'],
            'codigo_animal' => $validatedData['codigo_animal'],
            'sexo' => $validatedData['sexo'],
            'fecha_nacimiento' => $validatedData['fecha_nacimiento'],
            'procedencia' => $validatedData['procedencia'],
            'composicion_raza_id' => (int) $validatedData['composicion_raza_id'],
            'estado_inicial' => [
                'estado_salud_id' => (int) $validatedData['estado_inicial']['estado_salud_id'],
                'fecha_ini' => $validatedData['fecha_nacimiento'],
            ],
        ];

        $response = $this->animalesService->createAnimal($payload);

        if (!$response['success']) {
            return redirect()->back()
                ->withInput()
                ->with('error', $response['message']);
        }

        return redirect()->route('animales.index')
            ->with('success', '¡Animal creado exitosamente!');
    }

    /**
     * Muestra los detalles biográficos específicos de un animal.
     *
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(int $id)
    {
        $response = $this->animalesService->getAnimal($id);

        if (!$response['success']) {
            return redirect()->route('animales.index')->with('error', $response['message']);
        }

        $animal = $response['data'] ?? null;

        return view('animales.show', compact('animal'));
    }

    /**
     * Muestra el formulario para editar el perfil biográfico base del animal.
     * NOTA: No incluye estados ni etapas, ya que son recursos independientes.
     *
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(int $id)
    {
        $response = $this->animalesService->getAnimal($id);

        if (!$response['success']) {
            return redirect()->route('animales.index')->with('error', $response['message']);
        }

        $animal = $response['data'] ?? null;

        $rebanosResponse = $this->rebanosService->getRebanos();
        $rebanos = ($rebanosResponse['success'] ?? false) ? ($rebanosResponse['data']['data'] ?? $rebanosResponse['data'] ?? []) : [];

        $razasResponse = $this->animalesService->getRazas();
        $razas = ($razasResponse['success'] ?? false) ? ($razasResponse['data']['data'] ?? $razasResponse['data'] ?? []) : [];

        return view('animales.edit', compact('animal', 'rebanos', 'razas'));
    }

    /**
     * Procesa la actualización del perfil del animal en el sistema.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $validatedData = $request->validate([
            'rebano_id' => 'required|integer',
            'nombre' => 'required|string|max:255',
            'codigo_animal' => 'required|string|max:50',
            'sexo' => 'required|in:M,H',
            'fecha_nacimiento' => 'required|date',
            'procedencia' => 'required|string|max:255',
            'composicion_raza_id' => 'required|integer',
            'archivado' => 'boolean',
        ], [
            'rebano_id.required' => 'Debe seleccionar un rebaño',
            'nombre.required' => 'El nombre del animal es requerido',
            'codigo_animal.required' => 'El código del animal es requerido',
            'sexo.required' => 'El sexo del animal es requerido',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es requerida',
            'procedencia.required' => 'La procedencia es requerida',
            'composicion_raza_id.required' => 'Debe seleccionar una raza',
        ]);

        // Asegurarse de que el campo archivado se envíe correctamente (V2)
        $validatedData['archivado'] = $request->has('archivado') ? true : false;

        $response = $this->animalesService->updateAnimal($id, $validatedData);

        if (!$response['success']) {
            return redirect()->back()
                ->withInput()
                ->with('error', $response['message']);
        }

        return redirect()->route('animales.index')
            ->with('success', '¡Animal actualizado exitosamente!');
    }

    /**
     * Muestra el formulario para importar animales masivamente mediante CSV o TXT.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function importarForm(Request $request)
    {
        $fincasResponse = $this->fincasService->getFincas();
        $fincas = ($fincasResponse['success'] ?? false) ? ($fincasResponse['data']['data'] ?? $fincasResponse['data'] ?? []) : [];
        $idFinca = $request->query('finca_id') ?? session('selected_finca')['id'] ?? null;

        return view('animales.importar', compact('fincas', 'idFinca'));
    }

    /**
     * Procesa la importación masiva de animales.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importar(Request $request)
    {
        $request->validate([
            'finca_id' => 'required|integer',
            'archivo'  => 'required|file|max:10240',
        ], [
            'finca_id.required' => 'Debe seleccionar una finca de destino para los animales.',
            'finca_id.integer'  => 'El identificador de la finca debe ser numérico.',
            'archivo.required'  => 'Debe seleccionar un archivo .csv o .txt para procesar.',
            'archivo.file'      => 'El elemento subido no es un archivo válido.',
            'archivo.max'       => 'El tamaño del archivo no debe exceder los 10MB.',
        ]);

        $response = $this->animalesService->importarAnimales(
            (int) $request->input('finca_id'),
            $request->file('archivo')
        );

        if ($response['success'] ?? false) {
            return redirect()->route('animales.index', ['finca_id' => $request->input('finca_id')])
                ->with('success', $response['message'] ?? 'Animales importados exitosamente.');
        }

        $errorMessage = $response['message'] ?? 'Ocurrió un error al procesar el archivo.';
        $importErrors = $response['errors']['filas'] ?? ($response['errors'] ?? []);

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
            'Content-Disposition' => 'attachment; filename="plantilla_animales.csv"',
        ];

        $columns = ['codigo_animal', 'nombre', 'sexo', 'fecha_nacimiento', 'procedencia', 'rebano', 'raza', 'estado_salud', 'peso'];
        $samples = [
            ['AN-001', 'Vaca Mariposa', 'H', '2023-03-15', 'Local', 'Lote Produccion A', 'Holstein', 'Sano', '420'],
            ['AN-002', 'Toro Titan', 'M', '2022-11-20', 'Compra', 'Lote Reproduccion', 'Brahman', 'Sano', '550'],
            ['AN-003', 'Becerra Princesa', 'H', '2024-01-10', 'Nacimiento', 'Lote Cria', 'Carora', 'Sano', '110'],
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
     * Restaura un animal archivado a estado activo.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request, $id)
    {
        $response = $this->animalesService->restoreAnimal((int) $id);

        if ($response['success'] ?? false) {
            return redirect()->back()->with('success', $response['message'] ?? 'Animal restaurado exitosamente.');
        }

        return redirect()->back()->with('error', $response['message'] ?? 'Error al restaurar el animal.');
    }
}
