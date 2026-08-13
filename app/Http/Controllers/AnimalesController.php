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
        $idFinca  = $request->query('finca_id')  ? (int) $request->query('finca_id')  : null;
        $idRebano = $request->query('rebano_id') ? (int) $request->query('rebano_id') : null;
        $sexo     = $request->query('sexo', '');
        $nombre   = $request->query('nombre', '');

        // Obtener todos los animales del rebaño si está especificado
        $response = $this->animalesService->getAnimales($idRebano);
        $animales = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];

        // Cargar catálogos auxiliares (rebaños y fincas)
        $rebanosResponse = $this->rebanosService->getRebanos();
        $rebanos = ($rebanosResponse['success'] ?? false) ? ($rebanosResponse['data'] ?? []) : [];

        $fincasResponse = $this->fincasService->getFincas();
        // Fallback por si acaso la API responde con un formato anidado, pero por defecto se asume V2 ['data']
        $fincas = ($fincasResponse['success'] ?? false) ? ($fincasResponse['data'] ?? []) : [];

        // Construir mapa de Rebaño a Finca para validaciones y filtros en Javascript (UI)
        $mapaRebanoFinca = collect($rebanos)->keyBy('rebano_id')->map(fn($r) => $r['finca_id'] ?? null)->all();

        // Calcular estadísticas básicas en memoria
        $estadisticas = [
            'total'     => count($animales),
            'machos'    => count(array_filter($animales, fn($a) => ($a['sexo'] ?? '') === 'M')),
            'hembras'   => count(array_filter($animales, fn($a) => ($a['sexo'] ?? '') === 'H')), // Actualizado de 'F' a 'H' según la V2
            'activos'   => count(array_filter($animales, fn($a) => !($a['archivado'] ?? false))),
        ];

        return view('animales.index', compact(
            'animales', 'rebanos', 'fincas',
            'idFinca', 'idRebano', 'sexo', 'nombre',
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
        $rebanos = $rebanosResponse['success'] ? ($rebanosResponse['data'] ?? []) : [];

        $razasResponse = $this->animalesService->getRazas();
        $razas = $razasResponse['success'] ? ($razasResponse['data'] ?? []) : [];

        $estadosResponse = $this->animalesService->getEstadosSalud();
        $estadosData = $estadosResponse['success'] ? ($estadosResponse['data'] ?? []) : [];
        $estados = is_array($estadosData) ? array_filter($estadosData['data'], 'is_array') : [];

        $etapasResponse = $this->animalesService->getEtapas();
        $etapasData = $etapasResponse['success'] ? ($etapasResponse['data'] ?? []) : [];
        $etapas = is_array($etapasData) ? array_filter($etapasData, 'is_array') : [];

        return view('animales.create', compact('rebanos', 'razas', 'estados', 'etapas'));
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
            'estado_inicial.fecha_ini' => 'required|date',
            'etapa_inicial.etapa_id' => 'required|integer',
            'etapa_inicial.fecha_ini' => 'required|date',
        ], [
            'rebano_id.required' => 'Debe seleccionar un rebaño',
            'nombre.required' => 'El nombre del animal es requerido',
            'codigo_animal.required' => 'El código del animal es requerido',
            'sexo.required' => 'El sexo del animal es requerido',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es requerida',
            'procedencia.required' => 'La procedencia es requerida',
            'composicion_raza_id.required' => 'Debe seleccionar una raza',
            'estado_inicial.estado_salud_id.required' => 'Debe seleccionar un estado de salud inicial',
            'etapa_inicial.etapa_id.required' => 'Debe seleccionar una etapa inicial',
        ]);

        $response = $this->animalesService->createAnimal($validatedData);

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
        $rebanos = $rebanosResponse['success'] ? ($rebanosResponse['data'] ?? []) : [];

        $razasResponse = $this->animalesService->getRazas();
        $razas = $razasResponse['success'] ? ($razasResponse['data'] ?? []) : [];

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
}
