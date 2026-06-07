<?php

namespace App\Http\Controllers;

use App\Services\Contracts\MovimientoRebanoServiceInterface;
use Illuminate\Http\Request;

class MovimientoRebanoController extends Controller
{
    public function __construct(protected MovimientoRebanoServiceInterface $service) {}

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
            'animales'          => 'nullable|array',
            'animales.*'        => 'integer',
        ], [
            'id_Finca.required'          => 'La finca de origen es requerida.',
            'id_Rebano.required'         => 'El rebaño de origen es requerido.',
            'id_Finca_Destino.required'  => 'La finca de destino es requerida.',
            'id_Rebano_Destino.required' => 'El rebaño de destino es requerido.',
        ]);

        $data = $request->only(['id_Finca', 'id_Rebano', 'Rebano_Destino', 'id_Finca_Destino', 'id_Rebano_Destino', 'Comentario', 'animales']);

        $response = $this->service->create($data);

        if ($response['success'] ?? false) {
            return redirect()->route('movimiento-rebano.index')->with('success', 'Movimiento de rebaño registrado exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al crear el registro.');
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
        return back()->withInput()->with('error', $response['message'] ?? 'Error al actualizar.');
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('movimiento-rebano.index')->with('success', 'Movimiento de rebaño eliminado.');
        }
        return redirect()->route('movimiento-rebano.index')->with('error', $response['message'] ?? 'Error al eliminar.');
    }
}
