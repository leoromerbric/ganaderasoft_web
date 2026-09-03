<?php

namespace App\Http\Controllers;

use App\Services\Contracts\PalpacionServiceInterface;
use App\Services\Contracts\FincasServiceInterface;
use App\Services\Contracts\RebanosServiceInterface;
use App\Services\Contracts\EtapaServiceInterface;
use Illuminate\Http\Request;

class PalpacionController extends Controller
{
    public function __construct(
        protected PalpacionServiceInterface $service,
        protected FincasServiceInterface $fincasService,
        protected RebanosServiceInterface $rebanosService,
        protected EtapaServiceInterface $etapaService
    ) {}

    private function isFemale(array $animal): bool
    {
        $sexo = strtoupper((string)($animal['Sexo'] ?? $animal['sexo'] ?? ''));
        if ($sexo !== '') {
            return in_array($sexo, ['F', 'H', 'FEMENINO', 'HEMBRA'], true);
        }

        $label = strtolower(trim((string)($animal['sexo_label'] ?? $animal['genero'] ?? '')));
        return in_array($label, ['femenino', 'hembra'], true);
    }

    private function filterFemaleAnimals(array $animales): array
    {
        return array_values(array_filter($animales, fn (array $animal) => $this->isFemale($animal)));
    }

    private function isVetOrTech(array $persona): bool
    {
        $tipoVal = data_get($persona, 'tipo_trabajador.nombre') ?? data_get($persona, 'tipoTrabajador.nombre') ?? data_get($persona, 'Tipo_Trabajador') ?? data_get($persona, 'tipo_trabajador') ?? '';
        if (is_array($tipoVal)) {
            $tipoVal = $tipoVal['nombre'] ?? $tipoVal['Nombre'] ?? '';
        }
        $tipo = strtolower(trim((string) $tipoVal));

        if ($tipo === '') {
            return true;
        }

        return str_contains($tipo, 'veterinario') || str_contains($tipo, 'tecnico') || str_contains($tipo, 'técnico') || str_contains($tipo, 'inseminador') || str_contains($tipo, 'palpador') || str_contains($tipo, 'operario');
    }

    private function filterVetTechStaff(array $personal): array
    {
        $filtered = array_values(array_filter($personal, fn (array $persona) => $this->isVetOrTech($persona)));
        return !empty($filtered) ? $filtered : $personal;
    }

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
        $animalId    = $request->query('animal_id');
        $fincaId     = $request->query('finca_id');
        $rebanoId    = $request->query('rebano_id');
        $tipo        = $request->query('tipo');
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin    = $request->query('fecha_fin');

        $response    = $this->service->getList(
            $animalId ? (int)$animalId : null,
            $tipo,
            $fechaInicio,
            $fechaFin,
            $fincaId ? (int)$fincaId : null,
            $rebanoId ? (int)$rebanoId : null
        );
        $data        = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $palpaciones = (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) ? $data['data'] : $data;
        $animales    = $this->filterFemaleAnimals($this->service->getAnimales(['incluir_archivados' => true]));

        $fincasRes   = $this->fincasService->getFincas(['incluir_archivados' => true]);
        $fincas      = ($fincasRes['success'] ?? false) ? ($fincasRes['data']['data'] ?? $fincasRes['data'] ?? []) : [];

        $rebanosRes  = $this->rebanosService->getRebanos(['incluir_archivados' => true]);
        $rebanos     = ($rebanosRes['success'] ?? false) ? ($rebanosRes['data']['data'] ?? $rebanosRes['data'] ?? []) : [];

        $etapasRes   = $this->etapaService->getAll();
        $etapas      = ($etapasRes['success'] ?? false) ? ($etapasRes['data']['data'] ?? $etapasRes['data'] ?? []) : [];

        return view('palpacion.index', compact(
            'palpaciones', 'animales', 'fincas', 'rebanos', 'etapas',
            'animalId', 'fincaId', 'rebanoId', 'tipo', 'fechaInicio', 'fechaFin'
        ));
    }

    public function create(Request $request)
    {
        $animales = $this->filterFemaleAnimals($this->service->getAnimales());
        $personal = $this->filterVetTechStaff($this->service->getPersonalFinca());

        $fincasRes  = $this->fincasService->getFincas();
        $fincas     = ($fincasRes['success'] ?? false) ? ($fincasRes['data']['data'] ?? $fincasRes['data'] ?? []) : [];

        $rebanosRes = $this->rebanosService->getRebanos();
        $rebanos    = ($rebanosRes['success'] ?? false) ? ($rebanosRes['data']['data'] ?? $rebanosRes['data'] ?? []) : [];

        $etapasRes  = $this->etapaService->getAll();
        $etapas     = ($etapasRes['success'] ?? false) ? ($etapasRes['data']['data'] ?? $etapasRes['data'] ?? []) : [];

        $presetAnimalId = $request->query('animal_id');

        return view('palpacion.create', compact('animales', 'personal', 'fincas', 'rebanos', 'etapas', 'presetAnimalId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha'     => 'required|date',
            'tipo'      => 'nullable|string|max:25',
            'animal_id' => 'required|integer',
            'etapa_id'  => 'required|integer',
            'tecnico_id'=> 'nullable|integer',
        ], [
            'fecha.required'     => 'La fecha de la palpación es requerida.',
            'animal_id.required' => 'El animal es requerido.',
            'etapa_id.required'  => 'La etapa del animal es requerida.',
        ]);

        $data = [
            'personal_finca_id' => $request->filled('tecnico_id') ? (int)$request->input('tecnico_id') : null,
            'tipo'              => $request->input('tipo'),
            'fecha'             => $request->input('fecha'),
            'animal_id'         => (int)$request->input('animal_id'),
            'etapa_id'          => (int)$request->input('etapa_id'),
        ];

        $response = $this->service->create($data);

        if ($response['success'] ?? false) {
            return redirect()->route('palpacion.index')->with('success', 'Palpación registrada exitosamente.');
        }
        return back()->withInput()->with('error', $this->apiMessage($response, 'Error al crear el registro.'));
    }

    public function show(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('palpacion.index')->with('error', 'Registro no encontrado.');
        }
        $palpacion = $response['data'];

        $etapasRes  = $this->etapaService->getAll();
        $etapas     = ($etapasRes['success'] ?? false) ? ($etapasRes['data']['data'] ?? $etapasRes['data'] ?? []) : [];

        return view('palpacion.show', compact('palpacion', 'etapas'));
    }

    public function edit(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('palpacion.index')->with('error', 'Registro no encontrado.');
        }
        $palpacion = $response['data'];
        $animales  = $this->filterFemaleAnimals($this->service->getAnimales());
        $personal  = $this->filterVetTechStaff($this->service->getPersonalFinca());

        $fincasRes  = $this->fincasService->getFincas(['incluir_archivados' => true]);
        $fincas     = ($fincasRes['success'] ?? false) ? ($fincasRes['data']['data'] ?? $fincasRes['data'] ?? []) : [];

        $rebanosRes = $this->rebanosService->getRebanos(['incluir_archivados' => true]);
        $rebanos    = ($rebanosRes['success'] ?? false) ? ($rebanosRes['data']['data'] ?? $rebanosRes['data'] ?? []) : [];

        $etapasRes  = $this->etapaService->getAll();
        $etapas     = ($etapasRes['success'] ?? false) ? ($etapasRes['data']['data'] ?? $etapasRes['data'] ?? []) : [];

        return view('palpacion.edit', compact('palpacion', 'animales', 'personal', 'fincas', 'rebanos', 'etapas'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'tipo'       => 'nullable|string|max:25',
            'fecha'      => 'required|date',
            'tecnico_id' => 'nullable|integer',
        ], [
            'fecha.required' => 'La fecha de la palpación es requerida.',
        ]);

        $data = [
            'personal_finca_id' => $request->filled('tecnico_id') ? (int)$request->input('tecnico_id') : null,
            'tipo'              => $request->input('tipo'),
            'fecha'             => $request->input('fecha'),
        ];

        $response = $this->service->update($id, $data);
        if ($response['success'] ?? false) {
            return redirect()->route('palpacion.index')->with('success', 'Palpación actualizada exitosamente.');
        }
        return back()->withInput()->with('error', $this->apiMessage($response, 'Error al actualizar.'));
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('palpacion.index')->with('success', 'Palpación eliminada exitosamente.');
        }
        return redirect()->route('palpacion.index')->with('error', $this->apiMessage($response, 'Error al eliminar.'));
    }
}
