<?php

namespace App\Http\Controllers;

use App\Services\Contracts\SemenToroServiceInterface;
use App\Services\Contracts\FincasServiceInterface;
use App\Services\Contracts\RebanosServiceInterface;
use Illuminate\Http\Request;

class SemenToroController extends Controller
{
    public function __construct(
        protected SemenToroServiceInterface $service,
        protected FincasServiceInterface $fincasService,
        protected RebanosServiceInterface $rebanosService
    ) {}

    private function isMale(array $animal): bool
    {
        $sexo = strtoupper((string) ($animal['Sexo'] ?? $animal['sexo'] ?? ''));
        if ($sexo !== '') {
            return in_array($sexo, ['M', 'MACHO', 'MASCULINO'], true);
        }

        $label = strtolower(trim((string) ($animal['sexo_label'] ?? $animal['genero'] ?? '')));
        return in_array($label, ['masculino', 'macho'], true);
    }

    private function filterMaleAnimals(array $animales): array
    {
        $filtered = array_values(array_filter($animales, fn (array $animal) => $this->isMale($animal)));
        return !empty($filtered) ? $filtered : $animales;
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

    public function index(Request $request)
    {
        $toroId      = $request->query('toro_id');
        $activo      = $request->query('activo');
        $fincaId     = $request->query('finca_id');
        $rebanoId    = $request->query('rebano_id');
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin    = $request->query('fecha_fin');

        $response   = $this->service->getList(
            $toroId ? (int)$toroId : null,
            $activo !== null && $activo !== '' ? (bool)$activo : null,
            $fechaInicio,
            $fechaFin,
            $fincaId ? (int)$fincaId : null,
            $rebanoId ? (int)$rebanoId : null
        );
        $data       = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $semenToros = (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) ? $data['data'] : $data;
        $toros      = $this->filterMaleAnimals($this->service->getToros(['incluir_archivados' => true]));

        $fincasRes  = $this->fincasService->getFincas(['incluir_archivados' => true]);
        $fincas     = ($fincasRes['success'] ?? false) ? ($fincasRes['data']['data'] ?? $fincasRes['data'] ?? []) : [];

        $rebanosRes = $this->rebanosService->getRebanos(['incluir_archivados' => true]);
        $rebanos    = ($rebanosRes['success'] ?? false) ? ($rebanosRes['data']['data'] ?? $rebanosRes['data'] ?? []) : [];

        return view('semen-toro.index', compact(
            'semenToros', 'toros', 'fincas', 'rebanos',
            'toroId', 'activo', 'fincaId', 'rebanoId', 'fechaInicio', 'fechaFin'
        ));
    }

    public function create(Request $request)
    {
        $toros = $this->filterMaleAnimals($this->service->getToros());

        $fincasRes  = $this->fincasService->getFincas();
        $fincas     = ($fincasRes['success'] ?? false) ? ($fincasRes['data']['data'] ?? $fincasRes['data'] ?? []) : [];

        $rebanosRes = $this->rebanosService->getRebanos();
        $rebanos    = ($rebanosRes['success'] ?? false) ? ($rebanosRes['data']['data'] ?? $rebanosRes['data'] ?? []) : [];

        $presetAnimalId = $request->query('animal_id') ?? $request->query('toro_id');

        return view('semen-toro.create', compact('toros', 'fincas', 'rebanos', 'presetAnimalId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'animal_id' => 'required|integer',
            'estado'    => 'nullable',
            'fecha'     => 'nullable|date',
        ], [
            'animal_id.required' => 'El toro donante es requerido.',
        ]);

        $data = $request->only(['animal_id', 'estado', 'fecha']);
        if (!isset($data['estado']) || $data['estado'] === '') {
            $data['estado'] = true;
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
        $semen   = $response['data'];
        $toros   = $this->filterMaleAnimals($this->service->getToros());

        $fincasRes  = $this->fincasService->getFincas(['incluir_archivados' => true]);
        $fincas     = ($fincasRes['success'] ?? false) ? ($fincasRes['data']['data'] ?? $fincasRes['data'] ?? []) : [];

        $rebanosRes = $this->rebanosService->getRebanos(['incluir_archivados' => true]);
        $rebanos    = ($rebanosRes['success'] ?? false) ? ($rebanosRes['data']['data'] ?? $rebanosRes['data'] ?? []) : [];

        return view('semen-toro.edit', compact('semen', 'toros', 'fincas', 'rebanos'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'animal_id' => 'required|integer',
            'estado'    => 'nullable',
            'fecha'     => 'nullable|date',
        ], [
            'animal_id.required' => 'El toro donante es requerido.',
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
            return redirect()->route('semen-toro.index')->with('success', 'Registro de semen eliminado exitosamente.');
        }
        return redirect()->route('semen-toro.index')->with('error', $this->apiMessage($response, 'Error al eliminar.'));
    }
}
