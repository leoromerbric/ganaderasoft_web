<?php

namespace App\Http\Controllers;

use App\Services\Contracts\MovimientoRebanoServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controlador de Gestión de Movimientos de Rebaño.
 * 
 * Administra el traslado de animales entre fincas y rebaños,
 * interactuando exclusivamente con el servicio API v2.
 */
class MovimientoRebanoController extends Controller
{
    /**
     * Inyección de dependencias del servicio de movimientos de rebaño.
     */
    public function __construct(
        protected MovimientoRebanoServiceInterface $service
    ) {}

    /**
     * Procesa y extrae mensajes de error o fallback provenientes de la respuesta de la API.
     *
     * @param array<string, mixed> $response Respuesta cruda de la API.
     * @param string $fallback Mensaje por defecto si no se encuentra un error explícito.
     * @return string Mensaje limpio para presentar al usuario.
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
     * Muestra el listado de movimientos de rebaño con filtros aplicados.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $fincaId         = $request->query('finca_id')         ? (int) $request->query('finca_id')         : null;
        $rebanoId        = $request->query('rebano_id')        ? (int) $request->query('rebano_id')        : null;
        $fincaDestinoId  = $request->query('finca_destino_id') ? (int) $request->query('finca_destino_id') : null;
        $rebanoDestinoId = $request->query('rebano_destino_id')? (int) $request->query('rebano_destino_id'): null;

        $response    = $this->service->getList(null, null);
        $rawMovs     = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $movimientos = isset($rawMovs['data']) && is_array($rawMovs['data']) 
            ? $rawMovs['data'] 
            : (is_array($rawMovs) ? array_values(array_filter($rawMovs, 'is_array')) : []);

        $fincas      = array_values(array_filter($this->service->getFincas(['incluir_archivados' => true]), 'is_array'));
        $rebanos     = array_values(array_filter($this->service->getRebanos(['incluir_archivados' => true]), 'is_array'));

        if ($rebanoId && !$fincaId) {
            $rebObj = collect($rebanos)->firstWhere('id', $rebanoId);
            if ($rebObj) {
                $fincaId = $rebObj['finca_id'] ?? data_get($rebObj, 'finca.id') ?? null;
            }
        }

        if ($rebanoDestinoId && !$fincaDestinoId) {
            $rebDestObj = collect($rebanos)->firstWhere('id', $rebanoDestinoId);
            if ($rebDestObj) {
                $fincaDestinoId = $rebDestObj['finca_id'] ?? data_get($rebDestObj, 'finca.id') ?? null;
            }
        }

        $mapaFincas  = collect($fincas)->keyBy('id')->map(fn($f) => is_array($f) ? ($f['nombre'] ?? '') : '')->all();
        $mapaRebanos = collect($rebanos)->keyBy('id')->map(fn($r) => is_array($r) ? ($r['nombre'] ?? '') : '')->all();

        return view('movimiento-rebano.index', compact(
            'movimientos', 'fincas', 'rebanos',
            'fincaId', 'rebanoId', 'fincaDestinoId', 'rebanoDestinoId',
            'mapaFincas', 'mapaRebanos'
        ));
    }

    /**
     * Muestra el formulario para registrar un nuevo movimiento de rebaño.
     *
     * @return View
     */
    public function create(): View
    {
        $fincas   = array_values(array_filter($this->service->getFincas(), 'is_array'));
        $rebanos  = array_values(array_filter($this->service->getRebanos(), 'is_array'));
        $animales = array_values(array_filter($this->service->getAnimales(), 'is_array'));

        return view('movimiento-rebano.create', compact('fincas', 'rebanos', 'animales'));
    }

    /**
     * Almacena un nuevo registro de movimiento de rebaño.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'finca_id'          => 'required|integer',
            'rebano_id'         => 'required|integer',
            'finca_destino_id'  => 'required|integer',
            'rebano_destino_id' => 'required|integer',
            'rebano_destino'    => 'nullable|string|max:30',
            'comentario'        => 'nullable|string|max:40',
            'animales'          => 'required|array|min:1',
            'animales.*'        => 'integer',
        ], [
            'finca_id.required'          => 'La finca de origen es requerida.',
            'rebano_id.required'         => 'El rebaño de origen es requerido.',
            'finca_destino_id.required'  => 'La finca de destino es requerida.',
            'rebano_destino_id.required' => 'El rebaño de destino es requerido.',
            'animales.required'          => 'Debe seleccionar al menos un animal para mover.',
        ]);

        if ((int) $request->rebano_id === (int) $request->rebano_destino_id) {
            return back()->withInput()->with('error', 'El rebaño de destino debe ser diferente al rebaño de origen.');
        }

        $rebanos = collect($this->service->getRebanos());
        $rebanoOrigen  = $rebanos->first(fn ($rebano) => (int) ($rebano['id'] ?? 0) === (int) $request->rebano_id);
        $rebanoDestino = $rebanos->first(fn ($rebano) => (int) ($rebano['id'] ?? 0) === (int) $request->rebano_destino_id);

        if (!$rebanoOrigen || (int) data_get($rebanoOrigen, 'finca.id', $rebanoOrigen['finca_id'] ?? 0) !== (int) $request->finca_id) {
            return back()->withInput()->with('error', 'El rebaño de origen no pertenece a la finca de origen seleccionada.');
        }

        if (!$rebanoDestino || (int) data_get($rebanoDestino, 'finca.id', $rebanoDestino['finca_id'] ?? 0) !== (int) $request->finca_destino_id) {
            return back()->withInput()->with('error', 'El rebaño de destino no pertenece a la finca de destino seleccionada.');
        }

        $animalesSeleccionados = collect($request->input('animales', []))->map(fn ($id) => (int) $id)->unique()->values()->all();
        if (!empty($animalesSeleccionados)) {
            $animales = collect($this->service->getAnimales());
            $animalesInvalidos = $animales
                ->whereIn('id', $animalesSeleccionados)
                ->filter(function ($animal) use ($request) {
                    $rebanoId = data_get($animal, 'rebano.id') ?? data_get($animal, 'rebano_id');
                    return $rebanoId !== null && (int) $rebanoId !== (int) $request->rebano_id;
                });

            if ($animalesInvalidos->isNotEmpty()) {
                return back()->withInput()->with('error', 'Todos los animales seleccionados deben pertenecer al rebaño de origen.');
            }
        }

        $data = $request->only(['finca_id', 'rebano_id', 'finca_destino_id', 'rebano_destino_id', 'comentario', 'animales']);
        $data['rebano_destino'] = $rebanoDestino['nombre'] ?? $request->input('rebano_destino');

        $hashPayload = [
            'finca_id'          => (int) $request->finca_id,
            'rebano_id'         => (int) $request->rebano_id,
            'finca_destino_id'  => (int) $request->finca_destino_id,
            'rebano_destino_id' => (int) $request->rebano_destino_id,
            'comentario'        => (string) ($request->comentario ?? ''),
            'animales'          => $animalesSeleccionados,
        ];
        $requestHash = sha1(json_encode($hashPayload));
        $lastHash    = session('movimiento_rebano_last_hash');
        $lastTs      = (int) session('movimiento_rebano_last_ts', 0);
        if ($lastHash === $requestHash && (time() - $lastTs) <= 10) {
            return redirect()->route('movimiento-rebano.index')->with('success', 'Movimiento de rebaño registrado exitosamente.');
        }

        $response = $this->service->create($data);

        if ($response['success'] ?? false) {
            session([
                'movimiento_rebano_last_hash' => $requestHash,
                'movimiento_rebano_last_ts'   => time(),
            ]);
            return redirect()->route('movimiento-rebano.index')->with('success', 'Movimiento de rebaño registrado exitosamente.');
        }
        return back()->withInput()->with('error', $this->apiMessage($response, 'Error al crear el registro.'));
    }

    /**
     * Muestra la información detallada de un movimiento de rebaño específico.
     *
     * @param int $id Identificador del movimiento.
     * @return View|RedirectResponse
     */
    public function show(int $id): View|RedirectResponse
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('movimiento-rebano.index')->with('error', 'Movimiento no encontrado.');
        }

        $movimiento  = $response['data'];
        $fincas      = array_values(array_filter($this->service->getFincas(), 'is_array'));
        $rebanos     = array_values(array_filter($this->service->getRebanos(), 'is_array'));
        $mapaFincas  = collect($fincas)->keyBy('id')->map(fn($f) => is_array($f) ? ($f['nombre'] ?? '') : '')->all();
        $mapaRebanos = collect($rebanos)->keyBy('id')->map(fn($r) => is_array($r) ? ($r['nombre'] ?? '') : '')->all();

        return view('movimiento-rebano.show', compact('movimiento', 'mapaFincas', 'mapaRebanos'));
    }

    /**
     * Muestra el formulario para editar los campos permitidos de un movimiento.
     *
     * @param int $id Identificador del movimiento.
     * @return View|RedirectResponse
     */
    public function edit(int $id): View|RedirectResponse
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('movimiento-rebano.index')->with('error', 'Movimiento no encontrado.');
        }

        $movimiento = $response['data'];
        return view('movimiento-rebano.edit', compact('movimiento'));
    }

    /**
     * Actualiza la información modificable de un movimiento de rebaño existente.
     *
     * @param Request $request
     * @param int $id Identificador del movimiento.
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'rebano_destino' => 'nullable|string|max:30',
            'comentario'     => 'nullable|string|max:40',
        ]);

        $response = $this->service->update($id, $request->only(['rebano_destino', 'comentario']));

        if ($response['success'] ?? false) {
            return redirect()->route('movimiento-rebano.index')->with('success', 'Movimiento de rebaño actualizado.');
        }
        return back()->withInput()->with('error', $this->apiMessage($response, 'Error al actualizar.'));
    }

    /**
     * Elimina un movimiento de rebaño del registro.
     *
     * @param int $id Identificador del movimiento.
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('movimiento-rebano.index')->with('success', 'Movimiento de rebaño eliminado.');
        }
        return redirect()->route('movimiento-rebano.index')->with('error', $this->apiMessage($response, 'Error al eliminar.'));
    }
}
