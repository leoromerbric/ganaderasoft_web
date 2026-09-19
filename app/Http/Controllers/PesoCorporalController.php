<?php

namespace App\Http\Controllers;

use App\Services\Contracts\AnimalesServiceInterface;
use App\Services\Contracts\CambiosAnimalServiceInterface;
use App\Services\Contracts\FincasServiceInterface;
use App\Services\Contracts\PesoCorporalServiceInterface;
use App\Services\Contracts\RebanosServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Controlador para la Gestión de Pesos Corporales de los Animales.
 * 
 * Administra los controles de peso e interactúa exclusivamente
 * con la API v2.
 */
class PesoCorporalController extends Controller
{
    public function __construct(
        protected PesoCorporalServiceInterface $pesoCorporalService,
        protected AnimalesServiceInterface $animalesService,
        protected FincasServiceInterface $fincasService,
        protected RebanosServiceInterface $rebanosService,
        protected ?CambiosAnimalServiceInterface $cambiosAnimalService = null
    ) {
        if (!$this->cambiosAnimalService && app()->bound(CambiosAnimalServiceInterface::class)) {
            $this->cambiosAnimalService = app(CambiosAnimalServiceInterface::class);
        }
    }

    /**
     * Extrae mensajes legibles de las respuestas de la API.
     *
     * @param array<string, mixed> $response
     * @param string $fallback
     * @return string
     */
    private function apiMessage(array $response, string $fallback): string
    {
        if (!empty($response['message']) && is_string($response['message'])) {
            return $response['message'];
        }

        if (!empty($response['errors']) && is_array($response['errors'])) {
            $first = collect($response['errors'])->flatten()->first();
            if (is_string($first) && $first !== '') {
                return $first;
            }
        }

        return $fallback;
    }

    /**
     * Muestra el listado de registros de peso corporal con filtros y estadísticas.
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function index(Request $request): View|RedirectResponse
    {
        $animalId    = $request->query('animal_id') ? (int) $request->query('animal_id') : null;
        $fechaInicio = $request->query('fecha_inicio') ?: null;
        $fechaFin    = $request->query('fecha_fin') ?: null;

        $response = $this->pesoCorporalService->getPesosCorporales(null, null, null);

        if (!($response['success'] ?? false)) {
            return redirect()->route('dashboard')->with('error', $this->apiMessage($response, 'Error al obtener registros de peso corporal.'));
        }

        $animalesResponse = $this->animalesService->getAnimales(null, ['incluir_archivados' => true]);
        $rawAnimales      = $animalesResponse['data'] ?? [];
        $animales         = is_array($rawAnimales)
            ? (isset($rawAnimales['data']) && is_array($rawAnimales['data']) ? $rawAnimales['data'] : array_values(array_filter($rawAnimales, 'is_array')))
            : [];

        $animalesPorId = collect($animales)->keyBy(fn ($animal) => $animal['id'] ?? null);

        $rawPesos = $response['data'] ?? [];
        $pesosCorporales = collect(is_array($rawPesos) ? array_values(array_filter($rawPesos, 'is_array')) : [])
            ->map(function ($peso) use ($animalesPorId) {
                $animalIdRegistro = data_get($peso, 'animal.id') ?? data_get($peso, 'etapa_animal.animal_id') ?? null;
                $animal = $animalesPorId->get($animalIdRegistro, []);

                $rebanoId = data_get($peso, 'animal.rebano_id') ?? ($animal['rebano_id'] ?? data_get($animal, 'rebano.id'));
                $fincaId  = data_get($peso, 'animal.rebano.finca_id') ?? data_get($animal, 'rebano.finca_id') ?? data_get($animal, 'rebano.finca.id');

                $peso['animal_id']             = $animalIdRegistro;
                $peso['animal_nombre']         = data_get($peso, 'animal.nombre') ?? ($animal['nombre'] ?? null);
                $peso['animal_identificacion'] = data_get($peso, 'animal.codigo_animal') ?? ($animal['codigo_animal'] ?? null);
                $peso['rebano_id']             = $rebanoId;
                $peso['finca_id']              = $fincaId;

                return $peso;
            })->all();

        $pesos = collect($pesosCorporales)
            ->pluck('peso')
            ->filter(fn ($peso) => is_numeric($peso))
            ->map(fn ($peso) => (float) $peso);

        $estadisticas = [
            'total'         => count($pesosCorporales),
            'peso_promedio' => $pesos->isNotEmpty() ? number_format($pesos->avg(), 2, ',', '.') : '0,00',
            'peso_maximo'   => $pesos->isNotEmpty() ? number_format($pesos->max(), 2, ',', '.') : '0,00',
            'peso_minimo'   => $pesos->isNotEmpty() ? number_format($pesos->min(), 2, ',', '.') : '0,00',
        ];

        $fincasRes = $this->fincasService->getFincas(['incluir_archivados' => true]);
        $fincas = ($fincasRes['success'] ?? false) ? ($fincasRes['data']['data'] ?? $fincasRes['data'] ?? []) : [];

        $rebanosRes = $this->rebanosService->getRebanos(['incluir_archivados' => true]);
        $rebanos = ($rebanosRes['success'] ?? false) ? ($rebanosRes['data']['data'] ?? $rebanosRes['data'] ?? []) : [];

        $fincaId  = $request->query('finca_id') ? (int) $request->query('finca_id') : null;
        $rebanoId = $request->query('rebano_id') ? (int) $request->query('rebano_id') : null;

        if ($animalId && $animalesPorId->has($animalId)) {
            $an = $animalesPorId->get($animalId);
            if (!$fincaId) {
                $fincaId = (int) (data_get($an, 'rebano.finca_id') ?? data_get($an, 'rebano.finca.id') ?? 0) ?: null;
            }
            if (!$rebanoId) {
                $rebanoId = (int) ($an['rebano_id'] ?? data_get($an, 'rebano.id') ?? 0) ?: null;
            }
        } elseif ($rebanoId && !$fincaId) {
            $rebObj = collect($rebanos)->firstWhere('id', $rebanoId);
            if ($rebObj) {
                $fincaId = $rebObj['finca_id'] ?? data_get($rebObj, 'finca.id') ?? null;
            }
        }

        return view('peso-corporal.index', compact('pesosCorporales', 'animales', 'fincas', 'rebanos', 'animalId', 'fincaId', 'rebanoId', 'fechaInicio', 'fechaFin', 'estadisticas'));
    }

    /**
     * Muestra el formulario para registrar un nuevo peso corporal.
     *
     * @return View
     */
    public function create(): View
    {
        $animalesResponse = $this->animalesService->getAnimales();
        $rawAnimales      = $animalesResponse['data'] ?? [];
        $animales         = is_array($rawAnimales)
            ? (isset($rawAnimales['data']) && is_array($rawAnimales['data']) ? $rawAnimales['data'] : array_values(array_filter($rawAnimales, 'is_array')))
            : [];

        // Obtener historial de cambios de animales para reflejar la etapa más reciente si hubo transición
        $cambiosPorAnimal = collect();
        if ($this->cambiosAnimalService) {
            try {
                $cambios = $this->cambiosAnimalService->getList();
                $cambiosPorAnimal = collect($cambios)
                    ->filter(fn ($c) => !empty($c['etapa_cambio']))
                    ->sortByDesc(fn ($c) => ($c['fecha_cambio'] ?? '') . ' ' . ($c['created_at'] ?? ''))
                    ->groupBy(function ($c) {
                        return data_get($c, 'animal.id') ?? data_get($c, 'etapa_animal.animal_id') ?? $c['animal_id'] ?? null;
                    });
            } catch (\Throwable $e) {
                Log::warning('No se pudieron obtener los cambios de animal en PesoCorporalController@create: ' . $e->getMessage());
            }
        }

        foreach ($animales as &$animal) {
            $anId = $animal['id'] ?? null;
            if ($anId && $cambiosPorAnimal->has($anId)) {
                $ultimoCambio = $cambiosPorAnimal->get($anId)->first();
                if ($ultimoCambio && !empty($ultimoCambio['etapa_cambio'])) {
                    $animal['etapa_cambio_reciente'] = $ultimoCambio['etapa_cambio'];
                    $animal['etapa_cambio_id'] = data_get($ultimoCambio, 'animal_etapa.etapa_id') ?? data_get($ultimoCambio, 'etapa.id') ?? $ultimoCambio['etapa_id'] ?? null;
                }
            }
        }
        unset($animal);

        return view('peso-corporal.create', compact('animales'));
    }

    /**
     * Almacena un nuevo registro de peso corporal.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'fecha_peso' => 'required|date',
            'peso'       => 'required|numeric|min:0.01|max:9999',
            'animal_id'  => 'required|integer',
            'etapa_id'   => 'nullable|integer',
            'comentario' => 'nullable|string|max:255',
        ], [
            'fecha_peso.required' => 'La fecha de pesaje es requerida.',
            'fecha_peso.date'     => 'La fecha de pesaje debe ser una fecha válida.',
            'peso.required'       => 'El peso es requerido.',
            'peso.numeric'        => 'El peso debe ser un número.',
            'peso.min'            => 'El peso debe ser mayor a 0.',
            'peso.max'            => 'El peso no puede exceder 9999 kg.',
            'animal_id.required'  => 'El animal es requerido.',
            'comentario.max'      => 'El comentario no puede exceder 255 caracteres.',
        ]);

        $data = $request->only(['fecha_peso', 'peso', 'comentario', 'animal_id', 'etapa_id']);

        if (empty($data['etapa_id'])) {
            unset($data['etapa_id']);
        }

        $response = $this->pesoCorporalService->createPesoCorporal($data);

        if ($response['success'] ?? false) {
            $mensaje = 'Registro de peso creado exitosamente.';
            if (isset($response['data']['clasificacion_etaria'])) {
                $ce = $response['data']['clasificacion_etaria'];
                $etapaNombre = $ce['etapa_nombre'] ?? null;
                if ($etapaNombre) {
                    $mensaje .= " Clasificación etaria automática: {$etapaNombre}.";
                }
            }
            return redirect()->route('peso-corporal.index')->with('success', $mensaje);
        }

        return back()->withInput()->with('error', $this->apiMessage($response, 'Error al registrar el peso corporal.'));
    }

    /**
     * Muestra el formulario para editar un pesaje existente.
     *
     * @param int $id
     * @return View|RedirectResponse
     */
    public function edit(int $id): View|RedirectResponse
    {
        $response = $this->pesoCorporalService->getPesoCorporal($id);

        if (!($response['success'] ?? false)) {
            return redirect()->route('peso-corporal.index')->with('error', $this->apiMessage($response, 'Registro de peso no encontrado.'));
        }

        $pesoCorporal = $response['data'];

        $animalesResponse = $this->animalesService->getAnimales(null, ['incluir_archivados' => true]);
        $rawAnimales      = $animalesResponse['data'] ?? [];
        $animales         = is_array($rawAnimales)
            ? (isset($rawAnimales['data']) && is_array($rawAnimales['data']) ? $rawAnimales['data'] : array_values(array_filter($rawAnimales, 'is_array')))
            : [];

        return view('peso-corporal.edit', compact('pesoCorporal', 'animales'));
    }

    /**
     * Actualiza un registro de peso corporal.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'fecha_peso' => 'required|date',
            'peso'       => 'required|numeric|min:0.01|max:9999',
            'animal_id'  => 'required|integer',
            'etapa_id'   => 'nullable|integer',
            'comentario' => 'nullable|string|max:255',
        ], [
            'fecha_peso.required' => 'La fecha de pesaje es requerida.',
            'fecha_peso.date'     => 'La fecha de pesaje debe ser una fecha válida.',
            'peso.required'       => 'El peso es requerido.',
            'peso.numeric'        => 'El peso debe ser un número.',
            'peso.min'            => 'El peso debe ser mayor a 0.',
            'peso.max'            => 'El peso no puede exceder 9999 kg.',
            'animal_id.required'  => 'El animal es requerido.',
            'comentario.max'      => 'El comentario no puede exceder 255 caracteres.',
        ]);

        $data = $request->only(['fecha_peso', 'peso', 'comentario', 'animal_id', 'etapa_id']);

        if (empty($data['etapa_id'])) {
            unset($data['etapa_id']);
        }

        $response = $this->pesoCorporalService->updatePesoCorporal($id, $data);

        if ($response['success'] ?? false) {
            $mensaje = 'Registro de peso actualizado exitosamente.';
            if (isset($response['data']['clasificacion_etaria'])) {
                $ce = $response['data']['clasificacion_etaria'];
                $etapaNombre = $ce['etapa_nombre'] ?? null;
                if ($etapaNombre) {
                    $mensaje .= " Clasificación etaria automática: {$etapaNombre}.";
                }
            }
            return redirect()->route('peso-corporal.index')->with('success', $mensaje);
        }

        return back()->withInput()->with('error', $this->apiMessage($response, 'Error al actualizar el registro.'));
    }

    /**
     * Elimina un registro de peso corporal.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $response = $this->pesoCorporalService->deletePesoCorporal($id);

        if ($response['success'] ?? false) {
            return redirect()->route('peso-corporal.index')->with('success', 'Registro de peso eliminado exitosamente.');
        }

        return redirect()->route('peso-corporal.index')->with('error', $this->apiMessage($response, 'Error al eliminar el registro.'));
    }

    /**
     * Endpoint AJAX para consultar la etapa actual o más reciente de un animal.
     *
     * @param Request $request
     * @param int $id ID del animal
     * @return JsonResponse
     */
    public function getAnimalEtapa(Request $request, int $id): JsonResponse
    {
        try {
            $animal = null;
            if ($this->cambiosAnimalService) {
                $animal = $this->cambiosAnimalService->getAnimalById($id);
            }

            if (empty($animal)) {
                $res = $this->animalesService->getAnimal($id);
                $animal = $res['data'] ?? null;
            }

            if (empty($animal)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Animal no encontrado'
                ], 404);
            }

            $etapaActual = $animal['etapa_actual'] ?? null;

            // Verificar si existe un cambio de animal posterior
            if ($this->cambiosAnimalService) {
                try {
                    $cambios = $this->cambiosAnimalService->getList($id);
                    $ultimoCambio = collect($cambios)
                        ->filter(fn ($c) => !empty($c['etapa_cambio']))
                        ->sortByDesc(fn ($c) => ($c['fecha_cambio'] ?? '') . ' ' . ($c['created_at'] ?? ''))
                        ->first();

                    if ($ultimoCambio && !empty($ultimoCambio['etapa_cambio'])) {
                        $etapaActual = [
                            'id'           => data_get($ultimoCambio, 'animal_etapa_id') ?? data_get($etapaActual, 'id'),
                            'etapa_id'     => data_get($ultimoCambio, 'animal_etapa.etapa_id') ?? data_get($ultimoCambio, 'etapa.id') ?? data_get($etapaActual, 'etapa_id'),
                            'nombre'       => $ultimoCambio['etapa_cambio'],
                            'fecha_ini'    => $ultimoCambio['fecha_cambio'] ?? data_get($etapaActual, 'fecha_ini'),
                            'etapa'        => [
                                'id'     => data_get($ultimoCambio, 'animal_etapa.etapa_id') ?? data_get($ultimoCambio, 'etapa.id') ?? data_get($etapaActual, 'etapa.id'),
                                'nombre' => $ultimoCambio['etapa_cambio'],
                            ]
                        ];
                    }
                } catch (\Throwable $e) {
                    Log::warning("Error consultando cambios en getAnimalEtapa para animal {$id}: " . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'data'    => [
                    'animal'       => $animal,
                    'etapa_actual' => $etapaActual
                ]
            ]);
        } catch (\Throwable $e) {
            Log::error("Error en AJAX getAnimalEtapa para animal ID {$id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al consultar la etapa actual del animal'
            ], 500);
        }
    }
}