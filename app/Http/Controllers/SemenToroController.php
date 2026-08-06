<?php

namespace App\Http\Controllers;

use App\Services\Contracts\SemenToroServiceInterface;
use Illuminate\Http\Request;

class SemenToroController extends Controller
{
    public function __construct(protected SemenToroServiceInterface $service) {}

    public function index(Request $request)
    {
        $toroId  = $request->query('toro_id');
        $activo  = $request->query('activo');

        $response = $this->service->getList($toroId, $activo !== null ? (bool)$activo : null);
        $data = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $semenToros = (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) ? $data['data'] : $data;
        $toros    = $this->service->getToros();

        return view('semen-toro.index', compact('semenToros', 'toros', 'toroId', 'activo'));
    }

    public function create()
    {
        $toros = $this->service->getToros();
        return view('semen-toro.create', compact('toros'));
    }

    private function apiMessage(array $response, string $fallback): string
    {
        if (!empty($response['message']) && is_string($response['message'])) {
            if (!empty($response['errors']) && is_array($response['errors'])) {
                $first = collect($response['errors'])->flatten()->first();
                if (is_string($first) && $first !== '') {
                    return $first;
                }
            }
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

    public function store(Request $request)
    {
        $request->validate([
            'animal_id' => 'required|integer',
            'estado'    => 'nullable',
            'fecha'     => 'nullable|date',
        ], [
            'animal_id.required' => 'El toro es requerido.',
        ]);

        $data = $request->only(['animal_id', 'estado', 'fecha']);
        if (!isset($data['estado']) || $data['estado'] === '') {
            $data['estado'] = false;
        } else {
            $data['estado'] = filter_var($data['estado'], FILTER_VALIDATE_BOOLEAN);
        }

        $response = $this->service->create($data);

        if ($response['success'] ?? false) {
            return redirect()->route('semen-toro.index')->with('success', 'Semen de toro registrado exitosamente.');
        }
        return back()->withInput()->with('error', $this->apiMessage($response, 'Error al crear el registro.'));
    }

    public function show(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('semen-toro.index')->with('error', 'Registro no encontrado.');
        }
        $semen = $response['data'];
        return view('semen-toro.show', compact('semen'));
    }

    public function edit(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('semen-toro.index')->with('error', 'Registro no encontrado.');
        }
        $semen = $response['data'];
        $toros = $this->service->getToros();
        return view('semen-toro.edit', compact('semen', 'toros'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'animal_id' => 'nullable|integer',
            'estado'    => 'nullable',
            'fecha'     => 'nullable|date',
        ]);

        $data = $request->only(['animal_id', 'estado', 'fecha']);
        if (!isset($data['estado']) || $data['estado'] === '') {
            $data['estado'] = false;
        } else {
            $data['estado'] = filter_var($data['estado'], FILTER_VALIDATE_BOOLEAN);
        }

        $response = $this->service->update($id, $data);
        if ($response['success'] ?? false) {
            return redirect()->route('semen-toro.index')->with('success', 'Semen de toro actualizado exitosamente.');
        }
        return back()->withInput()->with('error', $this->apiMessage($response, 'Error al actualizar.'));
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('semen-toro.index')->with('success', 'Registro de semen eliminado.');
        }
        return redirect()->route('semen-toro.index')->with('error', $response['message'] ?? 'Error al eliminar.');
    }
}
