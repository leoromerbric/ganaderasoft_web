<?php

namespace App\Http\Controllers;

use App\Services\Contracts\MovimientoRebanoServiceInterface;
use Illuminate\Http\Request;

class MovimientoRebanoController extends Controller
{
    public function __construct(protected MovimientoRebanoServiceInterface $service) {}

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

    public function index(Request $request)
    {
        $fincaId  = $request->query('id_finca');
        $rebanoId = $request->query('id_rebano');

        $response    = $this->service->getList($fincaId, $rebanoId);
        $movimientos = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $fincas      = $this->service->getFincas();
        $rebanos     = $this->service->getRebanos();

        return view('movimiento-rebano.index', compact('movimientos', 'fincas', 'rebanos', 'fincaId', 'rebanoId'));
    }

    public function create()
    {
        $fincas  = $this->service->getFincas();
        $rebanos = $this->service->getRebanos();
        $animales = $this->service->getAnimales();
        return view('movimiento-rebano.create', compact('fincas', 'rebanos', 'animales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_Finca'          => 'required|integer',
            'id_Rebano'         => 'required|integer',
            'id_Finca_Destino'  => 'required|integer',
            'id_Rebano_Destino' => 'required|integer',
            'Rebano_Destino'    => 'nullable|string|max:30',
            'Comentario'        => 'nullable|string|max:40',
            'animales'          => 'required|array|min:1',
            'animales.*'        => 'integer',
        ], [
            'id_Finca.required'          => 'La finca de origen es requerida.',
            'id_Rebano.required'         => 'El rebaño de origen es requerido.',
            'id_Finca_Destino.required'  => 'La finca de destino es requerida.',
            'id_Rebano_Destino.required' => 'El rebaño de destino es requerido.',
            'animales.required'          => 'Debe seleccionar al menos un animal para mover.',
        ]);

        if ((int) $request->id_Finca === (int) $request->id_Finca_Destino) {
            return back()->withInput()->with('error', 'La finca de destino debe ser diferente a la finca de origen.');
        }

        $rebanos = collect($this->service->getRebanos());
        $rebanoOrigen = $rebanos->first(fn ($rebano) => (int) ($rebano['id_Rebano'] ?? 0) === (int) $request->id_Rebano);
        $rebanoDestino = $rebanos->first(fn ($rebano) => (int) ($rebano['id_Rebano'] ?? 0) === (int) $request->id_Rebano_Destino);

        if (!$rebanoOrigen || (int) ($rebanoOrigen['id_Finca'] ?? 0) !== (int) $request->id_Finca) {
            return back()->withInput()->with('error', 'El rebaño de origen no pertenece a la finca de origen seleccionada.');
        }

        if (!$rebanoDestino || (int) ($rebanoDestino['id_Finca'] ?? 0) !== (int) $request->id_Finca_Destino) {
            return back()->withInput()->with('error', 'El rebaño de destino no pertenece a la finca de destino seleccionada.');
        }

        $animalesSeleccionados = collect($request->input('animales', []))->map(fn ($id) => (int) $id)->unique()->values()->all();
        if (!empty($animalesSeleccionados)) {
            $animales = collect($this->service->getAnimales());
            $animalesInvalidos = $animales
                ->whereIn('id_Animal', $animalesSeleccionados)
                ->filter(function ($animal) use ($request) {
                    $rebanoId = data_get($animal, 'id_Rebano') ?? data_get($animal, 'rebano.id_Rebano');
                    return $rebanoId !== null && (int) $rebanoId !== (int) $request->id_Rebano;
                });

            if ($animalesInvalidos->isNotEmpty()) {
                return back()->withInput()->with('error', 'Todos los animales seleccionados deben pertenecer al rebaño de origen.');
            }
        }

        $data = $request->only(['id_Finca', 'id_Rebano', 'id_Finca_Destino', 'id_Rebano_Destino', 'Comentario', 'animales']);
        $data['Rebano_Destino'] = $rebanoDestino['Nombre'] ?? $request->input('Rebano_Destino');

        $hashPayload = [
            'id_Finca' => (int) $request->id_Finca,
            'id_Rebano' => (int) $request->id_Rebano,
            'id_Finca_Destino' => (int) $request->id_Finca_Destino,
            'id_Rebano_Destino' => (int) $request->id_Rebano_Destino,
            'Comentario' => (string) ($request->Comentario ?? ''),
            'animales' => $animalesSeleccionados,
        ];
        $requestHash = sha1(json_encode($hashPayload));
        $lastHash = session('movimiento_rebano_last_hash');
        $lastTs = (int) session('movimiento_rebano_last_ts', 0);
        if ($lastHash === $requestHash && (time() - $lastTs) <= 10) {
            return redirect()->route('movimiento-rebano.index')->with('success', 'Movimiento de rebaño registrado exitosamente.');
        }

        $response = $this->service->create($data);

        if ($response['success'] ?? false) {
            session([
                'movimiento_rebano_last_hash' => $requestHash,
                'movimiento_rebano_last_ts' => time(),
            ]);
            return redirect()->route('movimiento-rebano.index')->with('success', 'Movimiento de rebaño registrado exitosamente.');
        }
        return back()->withInput()->with('error', $this->apiMessage($response, 'Error al crear el registro.'));
    }

    public function show(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('movimiento-rebano.index')->with('error', 'Movimiento no encontrado.');
        }
        $movimiento = $response['data'];
        return view('movimiento-rebano.show', compact('movimiento'));
    }

    public function edit(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('movimiento-rebano.index')->with('error', 'Movimiento no encontrado.');
        }
        $movimiento = $response['data'];
        return view('movimiento-rebano.edit', compact('movimiento'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'Rebano_Destino' => 'nullable|string|max:30',
            'Comentario'     => 'nullable|string|max:40',
        ]);

        $response = $this->service->update($id, $request->only(['Rebano_Destino', 'Comentario']));

        if ($response['success'] ?? false) {
            return redirect()->route('movimiento-rebano.index')->with('success', 'Movimiento de rebaño actualizado.');
        }
        return back()->withInput()->with('error', $this->apiMessage($response, 'Error al actualizar.'));
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('movimiento-rebano.index')->with('success', 'Movimiento de rebaño eliminado.');
        }
        return redirect()->route('movimiento-rebano.index')->with('error', $this->apiMessage($response, 'Error al eliminar.'));
    }
}
