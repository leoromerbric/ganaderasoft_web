<?php

namespace App\Http\Controllers;

use App\Services\Contracts\DiagnosticoServiceInterface;
use App\Services\Contracts\FincasServiceInterface;
use App\Services\Contracts\RebanosServiceInterface;
use Illuminate\Http\Request;

class DiagnosticoController extends Controller
{
    public function __construct(
        protected DiagnosticoServiceInterface $service,
        protected FincasServiceInterface $fincasService,
        protected RebanosServiceInterface $rebanosService
    ) {}

    public function index(Request $request)
    {
        $animalId    = $request->query('animal_id');
        $fincaId     = $request->query('finca_id');
        $rebanoId    = $request->query('rebano_id');
        $tipo        = $request->query('tipo');
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin    = $request->query('fecha_fin');

        $response     = $this->service->getList();
        $data         = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $diagnosticos = (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) ? $data['data'] : $data;
        $animales     = $this->service->getAnimales(['incluir_archivados' => true]);

        $fincasRes = $this->fincasService->getFincas(['incluir_archivados' => true]);
        $fincas = ($fincasRes['success'] ?? false) ? ($fincasRes['data']['data'] ?? $fincasRes['data'] ?? []) : [];

        $rebanosRes = $this->rebanosService->getRebanos(['incluir_archivados' => true]);
        $rebanos = ($rebanosRes['success'] ?? false) ? ($rebanosRes['data']['data'] ?? $rebanosRes['data'] ?? []) : [];

        if ($animalId) {
            $an = collect($animales)->firstWhere('id', (int) $animalId);
            if ($an) {
                $rebanoId = $rebanoId ?: (data_get($an, 'rebano_id') ?? data_get($an, 'rebano.id'));
                $fincaId  = $fincaId ?: (data_get($an, 'rebano.finca_id') ?? data_get($an, 'rebano.finca.id'));
            }
        } elseif ($rebanoId && !$fincaId) {
            $rebObj = collect($rebanos)->firstWhere('id', (int) $rebanoId);
            if ($rebObj) {
                $fincaId = $rebObj['finca_id'] ?? data_get($rebObj, 'finca.id') ?? null;
            }
        }

        return view('diagnostico.index', compact('diagnosticos', 'animales', 'fincas', 'rebanos', 'animalId', 'fincaId', 'rebanoId', 'tipo', 'fechaInicio', 'fechaFin'));
    }

    public function create()
    {
        $animales = $this->service->getAnimales();
        return view('diagnostico.create', compact('animales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'nullable|string',
            'tipo'        => 'nullable|string|max:30',
            'fecha'       => 'nullable|date',
            'animal_id'   => 'required|integer',
            'etapa_id'    => 'required|integer',
        ], [
            'animal_id.required' => 'El animal es requerido.',
            'etapa_id.required'  => 'La etapa del animal es requerida.',
        ]);

        $response = $this->service->create($request->only([
            'descripcion', 'tipo', 'fecha', 'animal_id', 'etapa_id',
        ]));

        if ($response['success'] ?? false) {
            return redirect()->route('diagnostico.index')->with('success', 'Diagnóstico registrado exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al crear el registro.');
    }

    public function show(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('diagnostico.index')->with('error', 'Diagnóstico no encontrado.');
        }
        $diagnostico = $response['data'];
        return view('diagnostico.show', compact('diagnostico'));
    }

    public function edit(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('diagnostico.index')->with('error', 'Diagnóstico no encontrado.');
        }
        $diagnostico = $response['data'];
        $animales    = $this->service->getAnimales();
        return view('diagnostico.edit', compact('diagnostico', 'animales'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'descripcion' => 'nullable|string',
            'tipo'        => 'nullable|string|max:30',
            'fecha'       => 'nullable|date',
        ]);

        $response = $this->service->update($id, $request->only([
            'descripcion', 'tipo', 'fecha',
        ]));

        if ($response['success'] ?? false) {
            return redirect()->route('diagnostico.index')->with('success', 'Diagnóstico actualizado exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al actualizar.');
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('diagnostico.index')->with('success', 'Diagnóstico eliminado.');
        }
        return redirect()->route('diagnostico.index')->with('error', $response['message'] ?? 'Error al eliminar.');
    }
}
