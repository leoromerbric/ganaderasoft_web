<?php

namespace App\Http\Controllers;

use App\Services\Contracts\CambiosAnimalServiceInterface;
use App\Services\Contracts\ConfiguracionServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Controlador encargado de gestionar el flujo de interfaz de usuario para los cambios de etapa
 * y evolución física de los animales.
 */
class CambiosAnimalController extends Controller
{
    /**
     * Inyección de dependencias para los servicios de negocio de Cambios de Animal y Configuración.
     *
     * @param CambiosAnimalServiceInterface $cambiosAnimalService
     * @param ConfiguracionServiceInterface $configuracionService
     */
    public function __construct(
        protected CambiosAnimalServiceInterface $cambiosAnimalService,
        protected ConfiguracionServiceInterface $configuracionService
    ) {}

    /**
     * Muestra la lista paginada/filtrada de cambios de animales y sus métricas principales.
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function index(Request $request): View|RedirectResponse
    {
        try {
            $idAnimal = $request->query('animal_id') ? (int) $request->query('animal_id') : null;
            $idFinca  = $request->query('finca_id')  ? (int) $request->query('finca_id')  : null;
            $idRebano = $request->query('rebano_id') ? (int) $request->query('rebano_id') : null;

            // Cargar catálogos completos para filtros y selects
            $animalesTodos = $this->cambiosAnimalService->getAnimales();
            $fincas        = $this->cambiosAnimalService->getFincas();
            $rebanos       = $this->cambiosAnimalService->getRebanos();

            // Filtrar rebaños por la finca seleccionada si existe
            if ($idFinca) {
                $rebanos = array_values(array_filter($rebanos, fn ($r) => (int) ($r['finca_id'] ?? 0) === $idFinca));
            }

            // Filtrar animales según selección de rebaño o finca
            $animales = $animalesTodos;
            if ($idRebano) {
                $animales = array_values(array_filter($animales, fn ($a) => (int) ($a['rebano_id'] ?? 0) === $idRebano));
            } elseif ($idFinca) {
                $animales = array_values(array_filter($animales, function ($a) use ($idFinca) {
                    $fincaId = data_get($a, 'rebano.finca_id') ?? data_get($a, 'rebano.finca.id');
                    return (int) $fincaId === $idFinca;
                }));
            }

            // Colección de IDs de animales autorizados por el filtro de ubicación
            $idsPermitidos = array_column($animales, 'id');

            // Consultar historial de cambios y aplicar filtros cruzados
            $cambiosTodos = $this->cambiosAnimalService->getList($idAnimal, null);
            $cambios = ($idFinca || $idRebano)
                ? array_values(array_filter($cambiosTodos, fn ($c) => in_array($c['animal_id'] ?? null, $idsPermitidos, true)))
                : $cambiosTodos;

            // Mapear un diccionario [id => nombre] para despliegue en filtros y respaldos
            $mapaAnimales = collect($animalesTodos)
                ->filter(fn ($a) => isset($a['id']))
                ->mapWithKeys(fn ($a) => [(int) $a['id'] => $a['nombre'] ?? ('Animal #' . $a['id'])])
                ->all();

            // Enriquecer la lista de cambios con el nombre del animal entregado por la API v2
            $cambios = array_map(function ($c) use ($mapaAnimales) {
                $animalId = $c['animal_id'] ?? null;
                $c['animal_nombre'] = data_get($c, 'animal.nombre')
                    ?? ($animalId ? ($mapaAnimales[(int) $animalId] ?? null) : null)
                    ?? ($animalId ? ('Animal #' . $animalId) : 'Animal no asignado');

                return $c;
            }, $cambios);

            $estadisticas = $this->cambiosAnimalService->getEstadisticas();

            return view('cambios-animal.index', compact(
                'cambios', 'estadisticas', 'animales', 'idAnimal',
                'fincas', 'rebanos', 'idFinca', 'idRebano', 'mapaAnimales'
            ));
        } catch (\Exception $e) {
            Log::error('Error al cargar la lista de cambios de animales: ' . $e->getMessage(), [
                'exception' => $e,
                'request'   => $request->all()
            ]);

            return view('cambios-animal.index', [
                'cambios'      => [],
                'estadisticas' => [
                    'total_cambios'   => 0,
                    'por_etapa'       => [],
                    'ultimos_30_dias' => 0,
                    'promedio_peso'   => 0,
                    'promedio_altura' => 0
                ],
                'animales'     => [],
                'idAnimal'     => null,
                'fincas'       => [],
                'rebanos'      => [],
                'idFinca'      => null,
                'idRebano'     => null,
                'mapaAnimales' => [],
            ])->with('error', 'Ocurrió un error al cargar la información de cambios de animales.');
        }
    }

    /**
     * Muestra el formulario para registrar un nuevo cambio de etapa/desarrollo.
     *
     * @return View|RedirectResponse
     */
    public function create(): View|RedirectResponse
    {
        try {
            $animales = $this->cambiosAnimalService->getAnimales();
            $etapas   = $this->configuracionService->getEtapas();

            return view('cambios-animal.create', compact('animales', 'etapas'));
        } catch (\Exception $e) {
            Log::error('Error al preparar el formulario de creación de cambio de animal: ' . $e->getMessage());

            return view('cambios-animal.create', [
                'animales' => [],
                'etapas'   => []
            ])->with('error', 'Error al cargar las opciones del formulario.');
        }
    }

    /**
     * Valida y envía el nuevo registro de cambio a la API v2.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'animal_id' => [
                'required',
                'integer',
                'min:1'
            ],
            'etapa_id' => [
                'required',
                'integer',
                'min:1'
            ],
            'animal_etapa_id' => [
                'nullable',
                'integer'
            ],
            'fecha_cambio' => [
                'required',
                'date',
                'before_or_equal:today'
            ],
            'etapa_cambio' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+$/'
            ],
            'peso' => [
                'nullable',
                'numeric',
                'min:1',
                'max:2000'
            ],
            'altura' => [
                'nullable',
                'numeric',
                'min:10',
                'max:300'
            ],
            'comentario' => [
                'nullable',
                'string',
                'max:500'
            ]
        ], [
            'animal_id.required'           => 'Debe seleccionar un animal.',
            'animal_id.integer'            => 'El animal seleccionado no es válido.',
            'animal_id.min'                => 'Debe seleccionar un animal válido.',
            'etapa_id.required'            => 'Debe seleccionar una etapa.',
            'etapa_id.integer'             => 'La etapa seleccionada no es válida.',
            'etapa_id.min'                 => 'Debe seleccionar una etapa válida.',
            'fecha_cambio.required'        => 'La fecha del cambio es obligatoria.',
            'fecha_cambio.date'            => 'La fecha del cambio debe ser una fecha válida.',
            'fecha_cambio.before_or_equal' => 'La fecha del cambio no puede ser futura.',
            'etapa_cambio.required'        => 'El nombre de la etapa es obligatorio.',
            'etapa_cambio.string'          => 'El nombre de la etapa debe ser texto.',
            'etapa_cambio.max'             => 'El nombre de la etapa no puede exceder 50 caracteres.',
            'etapa_cambio.regex'           => 'El nombre de la etapa solo puede contener letras y espacios.',
            'peso.numeric'                 => 'El peso debe ser un número válido.',
            'peso.min'                     => 'El peso mínimo es 1 kg.',
            'peso.max'                     => 'El peso máximo es 2000 kg.',
            'altura.numeric'               => 'La altura debe ser un número válido.',
            'altura.min'                   => 'La altura mínima es 10 cm.',
            'altura.max'                   => 'La altura máxima es 300 cm.',
            'comentario.string'            => 'El comentario debe ser texto.',
            'comentario.max'               => 'El comentario no puede exceder 500 caracteres.'
        ]);

        try {
            $response = $this->cambiosAnimalService->create($validatedData);

            if ($response['success'] ?? false) {
                return redirect()->route('cambios-animal.index')
                    ->with('success', 'Cambio de animal registrado exitosamente.');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $response['message'] ?? 'Error al registrar el cambio de animal.');
        } catch (\Exception $e) {
            Log::error('Error en CambiosAnimalController@store: ' . $e->getMessage(), [
                'exception' => $e,
                'payload'   => $validatedData
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error interno al procesar la solicitud.');
        }
    }

    /**
     * Muestra la vista detallada de un registro de cambio específico.
     *
     * @param int $id ID del cambio
     * @return View|RedirectResponse
     */
    public function show(int $id): View|RedirectResponse
    {
        try {
            $response = $this->cambiosAnimalService->getById($id);

            if (!($response['success'] ?? false) || empty($response['data'])) {
                return redirect()->route('cambios-animal.index')
                    ->with('error', 'El registro de cambio no fue encontrado.');
            }

            $cambio = $response['data'];

            return view('cambios-animal.show', compact('cambio'));
        } catch (\Exception $e) {
            Log::error("Error al visualizar el cambio de animal ID {$id}: " . $e->getMessage());

            return redirect()->route('cambios-animal.index')
                ->with('error', 'Error al consultar los detalles del cambio.');
        }
    }

    /**
     * Maneja las solicitudes de eliminación de cambios.
     *
     * @param int $id ID del cambio
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        return redirect()->route('cambios-animal.index')
            ->with('info', 'La eliminación de registros de cambio no está permitida por políticas de auditoría.');
    }

    /**
     * Endpoint AJAX para consultar la etapa actual de un animal específico.
     *
     * @param Request $request
     * @param int $id ID del animal
     * @return JsonResponse
     */
    public function getAnimalEtapa(Request $request, int $id): JsonResponse
    {
        try {
            $animal = $this->cambiosAnimalService->getAnimalById($id);

            if (empty($animal)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Animal no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data'    => [
                    'animal'       => $animal,
                    'etapa_actual' => $animal['etapa_actual'] ?? null
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("Error en AJAX getAnimalEtapa para animal ID {$id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al consultar la etapa actual del animal'
            ], 500);
        }
    }
}