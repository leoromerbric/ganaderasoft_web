<?php

namespace App\Http\Controllers;

use App\Services\Contracts\CambiosAnimalServiceInterface;
use App\Services\Contracts\ConfiguracionServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CambiosAnimalController extends Controller
{
    protected $cambiosAnimalService;
    protected $configuracionService;

    public function __construct(
        CambiosAnimalServiceInterface $cambiosAnimalService,
        ConfiguracionServiceInterface $configuracionService
    ) {
        $this->cambiosAnimalService = $cambiosAnimalService;
        $this->configuracionService = $configuracionService;
    }

    /**
     * Muestra la lista de cambios de animales
     */
    public function index(Request $request)
    {
        try {
            Log::info('CambiosAnimalController@index - Iniciando carga de datos');
            
            $idAnimal = $request->get('animal_id');
            
            Log::info('CambiosAnimalController@index - Filtro animal recibido', ['animal_id' => $idAnimal]);
            
            // Obtener animales (siempre todos para el dropdown)
            $animales = $this->cambiosAnimalService->getAnimales();
            Log::info('CambiosAnimalController@index - Animales obtenidos: ' . count($animales));
            
            // Obtener cambios filtrados por animal si se especifica
            $cambios = $this->cambiosAnimalService->getList($idAnimal, null);
            Log::info('CambiosAnimalController@index - Cambios obtenidos: ' . count($cambios));
            
            $estadisticas = $this->cambiosAnimalService->getEstadisticas();
            
            Log::info('CambiosAnimalController@index - Completado exitosamente', [
                'cambios_count' => count($cambios),
                'animales_count' => count($animales)
            ]);
            
            return view('cambios-animal.index', compact('cambios', 'estadisticas', 'animales', 'idAnimal'));
        } catch (\Exception $e) {
            Log::error('Error en CambiosAnimalController@index: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return view('cambios-animal.index', [
                'cambios' => [],
                'estadisticas' => [
                    'total_cambios' => 0,
                    'por_etapa' => [],
                    'ultimos_30_dias' => 0,
                    'promedio_peso' => 0,
                    'promedio_altura' => 0
                ],
                'animales' => [],
                'idAnimal' => null
            ])->with('error', 'Error al cargar los cambios de animales');
        }
    }

    /**
     * Muestra el formulario de creación de cambio
     */
    public function create()
    {
        try {
            $animales = $this->cambiosAnimalService->getAnimales();
            $etapas = $this->configuracionService->getEtapas();
            
            return view('cambios-animal.create', compact('animales', 'etapas'));
        } catch (\Exception $e) {
            Log::error('Error en CambiosAnimalController@create: ' . $e->getMessage());
            
            return view('cambios-animal.create', [
                'animales' => [],
                'etapas' => []
            ])->with('error', 'Error al cargar los datos del formulario. Por favor, intente de nuevo.');
        }
    }

    /**
     * Almacena un nuevo cambio de animal
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cambios_etapa_anid' => [
                'required',
                'integer',
                'min:1'
            ],
            'cambios_etapa_etid' => [
                'required',
                'integer',
                'min:1'
            ],
            'Fecha_Cambio' => [
                'required',
                'date',
                'before_or_equal:today'
            ],
            'Etapa_Cambio' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+$/'
            ],
            'Peso' => [
                'nullable',
                'numeric',
                'min:1',
                'max:2000'
            ],
            'Altura' => [
                'nullable',
                'numeric',
                'min:10',
                'max:300'
            ],
            'Comentario' => [
                'nullable',
                'string',
                'max:500'
            ]
        ], [
            // Mensajes de validación en español
            'cambios_etapa_anid.required' => 'Debe seleccionar un animal',
            'cambios_etapa_anid.integer' => 'El animal seleccionado no es válido',
            'cambios_etapa_anid.min' => 'Debe seleccionar un animal válido',
            'cambios_etapa_etid.required' => 'Debe seleccionar una etapa',
            'cambios_etapa_etid.integer' => 'La etapa seleccionada no es válida',
            'cambios_etapa_etid.min' => 'Debe seleccionar una etapa válida',
            'Fecha_Cambio.required' => 'La fecha del cambio es obligatoria',
            'Fecha_Cambio.date' => 'La fecha del cambio debe ser una fecha válida',
            'Fecha_Cambio.before_or_equal' => 'La fecha del cambio no puede ser futura',
            'Etapa_Cambio.required' => 'El nombre de la etapa es obligatorio',
            'Etapa_Cambio.string' => 'El nombre de la etapa debe ser texto',
            'Etapa_Cambio.max' => 'El nombre de la etapa no puede exceder 50 caracteres',
            'Etapa_Cambio.regex' => 'El nombre de la etapa solo puede contener letras y espacios',
            'Peso.numeric' => 'El peso debe ser un número válido',
            'Peso.min' => 'El peso mínimo es 1 kg',
            'Peso.max' => 'El peso máximo es 2000 kg',
            'Altura.numeric' => 'La altura debe ser un número válido',
            'Altura.min' => 'La altura mínima es 10 cm',
            'Altura.max' => 'La altura máxima es 300 cm',
            'Comentario.string' => 'El comentario debe ser texto',
            'Comentario.max' => 'El comentario no puede exceder 500 caracteres'
        ]);

        try {
            $response = $this->cambiosAnimalService->create($validatedData);

            if ($response['success']) {
                return redirect()->route('cambios-animal.index')
                    ->with('success', 'Cambio de animal registrado exitosamente');
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $response['message'] ?? 'Error al registrar el cambio');
            }
        } catch (\Exception $e) {
            Log::error('Error en CambiosAnimalController@store: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error interno del servidor. Inténtelo de nuevo.');
        }
    }

    /**
     * Muestra los detalles de un cambio específico
     */
    public function show(string $id)
    {
        try {
            $cambio = $this->cambiosAnimalService->getById($id);
            
            if (empty($cambio)) {
                return redirect()->route('cambios-animal.index')
                    ->with('error', 'Cambio no encontrado');
            }
            
            return view('cambios-animal.show', compact('cambio'));
        } catch (\Exception $e) {
            Log::error('Error en CambiosAnimalController@show: ' . $e->getMessage());
            
            return redirect()->route('cambios-animal.index')
                ->with('error', 'Error al cargar los detalles del cambio');
        }
    }

    /**
     * Elimina un cambio de animal
     */
    public function destroy(string $id)
    {
        try {
            // Por el momento, no implementamos eliminación ya que no hay endpoint DELETE en la API
            return redirect()->route('cambios-animal.index')
                ->with('info', 'La eliminación de cambios no está disponible por políticas de auditoría');
        } catch (\Exception $e) {
            Log::error('Error en CambiosAnimalController@destroy: ' . $e->getMessage());
            
            return redirect()->route('cambios-animal.index')
                ->with('error', 'Error al procesar la solicitud');
        }
    }

    /**
     * Obtiene la etapa actual de un animal específico (AJAX)
     */
    public function getAnimalEtapa(Request $request, $id)
    {
        try {
            Log::info('CambiosAnimalController@getAnimalEtapa - Obteniendo etapa para animal: ' . $id);
            
            $animal = $this->cambiosAnimalService->getAnimalById($id);
            
            if (empty($animal)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Animal no encontrado'
                ], 404);
            }
            
            Log::info('CambiosAnimalController@getAnimalEtapa - Animal obtenido', [
                'animal_id' => $id,
                'has_etapa_actual' => isset($animal['etapa_actual']),
                'etapa_actual_structure' => $animal['etapa_actual'] ?? 'null'
            ]);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'animal' => $animal,
                    'etapa_actual' => $animal['etapa_actual'] ?? null
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error en getAnimalEtapa: ' . $e->getMessage(), ['animal_id' => $id]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener etapa del animal'
            ], 500);
        }
    }
}