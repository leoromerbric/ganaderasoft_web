<?php

namespace App\Http\Controllers;

use App\Services\Contracts\AnimalesServiceInterface;
use App\Services\Contracts\FincasServiceInterface;
use App\Services\Contracts\PesoCorporalServiceInterface;
use App\Services\Contracts\RebanosServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        protected RebanosServiceInterface $rebanosService
    ) {}

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

        $response = $this->pesoCorporalService->getPesosCorporales($animalId, $fechaInicio, $fechaFin);

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

        return view('peso-corporal.index', compact('pesosCorporales', 'animales', 'fincas', 'rebanos', 'animalId', 'fechaInicio', 'fechaFin', 'estadisticas'));
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
}