<?php

namespace App\Http\Controllers;

use App\Services\Contracts\RegistroCeloServiceInterface;
use App\Services\Contracts\FincasServiceInterface;
use App\Services\Contracts\RebanosServiceInterface;
use App\Services\Contracts\EtapaServiceInterface;
use Illuminate\Http\Request;

class RegistroCeloController extends Controller
{
    public function __construct(
        protected RegistroCeloServiceInterface $service,
        protected FincasServiceInterface $fincasService,
        protected RebanosServiceInterface $rebanosService,
        protected EtapaServiceInterface $etapaService
    ) {}

    private function isFemale(array $animal): bool
    {
        $sexo = strtoupper((string) ($animal['Sexo'] ?? $animal['sexo'] ?? ''));
        if ($sexo !== '') {
            return in_array($sexo, ['F', 'H', 'FEMENINO', 'HEMBRA'], true);
        }

        $label = strtolower(trim((string) ($animal['sexo_label'] ?? $animal['genero'] ?? '')));
        return in_array($label, ['femenino', 'hembra'], true);
    }

    private function filterFemaleAnimals(array $animales): array
    {
        return array_values(array_filter($animales, fn (array $animal) => $this->isFemale($animal)));
    }

    public function index(Request $request)
    {
        $animalId    = $request->query('animal_id');
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin    = $request->query('fecha_fin');

        $response  = $this->service->getList($animalId, $fechaInicio, $fechaFin);
        $data      = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $registros = (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) ? $data['data'] : $data;
        $animales  = $this->filterFemaleAnimals($this->service->getAnimales(['incluir_archivados' => true]));

        $fincasRes = $this->fincasService->getFincas(['incluir_archivados' => true]);
        $fincas = ($fincasRes['success'] ?? false) ? ($fincasRes['data']['data'] ?? $fincasRes['data'] ?? []) : [];

        $rebanosRes = $this->rebanosService->getRebanos(['incluir_archivados' => true]);
        $rebanos    = $rebanosRes['data'] ?? [];
        $etapasRes  = $this->etapaService->getAll();
        $etapas     = $etapasRes['data']['data'] ?? $etapasRes['data'] ?? [];

        return view('registro-celo.index', compact('registros', 'animales', 'animalId', 'fechaInicio', 'fechaFin', 'fincas', 'rebanos', 'etapas'));
    }

    public function create()
    {
        $animales = $this->filterFemaleAnimals($this->service->getAnimales());
        
        $fincasRes  = $this->fincasService->getFincas();
        $fincas     = $fincasRes['data'] ?? [];
        $rebanosRes = $this->rebanosService->getRebanos();
        $rebanos    = $rebanosRes['data'] ?? [];
        $etapasRes  = $this->etapaService->getAll();
        $etapas     = $etapasRes['data']['data'] ?? $etapasRes['data'] ?? [];

        return view('registro-celo.create', compact('animales', 'fincas', 'rebanos', 'etapas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha'       => 'required|date',
            'observacion' => 'nullable|string|max:100',
            'animal_id'   => 'required|integer',
            'etapa_id'    => 'nullable|integer',
        ], [
            'fecha.required'     => 'La fecha de celo es requerida.',
            'animal_id.required' => 'El animal es requerido.',
        ]);

        $response = $this->service->create($request->only(['fecha', 'observacion', 'animal_id', 'etapa_id']));

        if ($response['success'] ?? false) {
            return redirect()->route('registro-celo.index')->with('success', 'Registro de celo creado exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al crear el registro.');
    }

    public function show(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('registro-celo.index')->with('error', 'Registro no encontrado.');
        }
        $registro = $response['data'];
        $etapasRes = $this->etapaService->getAll();
        $etapas = $etapasRes['data']['data'] ?? $etapasRes['data'] ?? [];
        return view('registro-celo.show', compact('registro', 'etapas'));
    }

    public function edit(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('registro-celo.index')->with('error', 'Registro no encontrado.');
        }
        $registro = $response['data'];
        $animales = $this->filterFemaleAnimals($this->service->getAnimales());

        $fincasRes  = $this->fincasService->getFincas();
        $fincas     = $fincasRes['data'] ?? [];
        $rebanosRes = $this->rebanosService->getRebanos();
        $rebanos    = $rebanosRes['data'] ?? [];
        $etapasRes  = $this->etapaService->getAll();
        $etapas     = $etapasRes['data']['data'] ?? $etapasRes['data'] ?? [];

        return view('registro-celo.edit', compact('registro', 'animales', 'fincas', 'rebanos', 'etapas'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'fecha'       => 'required|date',
            'observacion' => 'nullable|string|max:100',
        ], [
            'fecha.required' => 'La fecha de celo es requerida.',
        ]);

        $response = $this->service->update($id, $request->only(['fecha', 'observacion']));

        if ($response['success'] ?? false) {
            return redirect()->route('registro-celo.index')->with('success', 'Registro de celo actualizado exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al actualizar.');
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('registro-celo.index')->with('success', 'Registro de celo eliminado.');
        }
        return redirect()->route('registro-celo.index')->with('error', $response['message'] ?? 'Error al eliminar.');
    }
}
