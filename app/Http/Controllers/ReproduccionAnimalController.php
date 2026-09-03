<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ReproduccionAnimalServiceInterface;
use App\Services\Contracts\FincasServiceInterface;
use App\Services\Contracts\RebanosServiceInterface;
use App\Services\Contracts\EtapaServiceInterface;
use Illuminate\Http\Request;

class ReproduccionAnimalController extends Controller
{
    public function __construct(
        protected ReproduccionAnimalServiceInterface $service,
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
        $fincaId     = $request->query('finca_id');
        $rebanoId    = $request->query('rebano_id');
        $tipo        = $request->query('tipo');
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin    = $request->query('fecha_fin');

        $response       = $this->service->getList(
            $animalId ? (int)$animalId : null,
            $tipo,
            $fechaInicio,
            $fechaFin,
            $fincaId ? (int)$fincaId : null,
            $rebanoId ? (int)$rebanoId : null
        );
        $data           = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $reproducciones = (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) ? $data['data'] : $data;
        $animales       = $this->filterFemaleAnimals($this->service->getAnimales(['incluir_archivados' => true]));

        $fincasRes  = $this->fincasService->getFincas(['incluir_archivados' => true]);
        $fincas     = ($fincasRes['success'] ?? false) ? ($fincasRes['data']['data'] ?? $fincasRes['data'] ?? []) : [];

        $rebanosRes = $this->rebanosService->getRebanos(['incluir_archivados' => true]);
        $rebanos    = ($rebanosRes['success'] ?? false) ? ($rebanosRes['data']['data'] ?? $rebanosRes['data'] ?? []) : [];

        $etapasRes  = $this->etapaService->getAll();
        $etapas     = ($etapasRes['success'] ?? false) ? ($etapasRes['data']['data'] ?? $etapasRes['data'] ?? []) : [];

        return view('reproduccion-animal.index', compact(
            'reproducciones', 'animales', 'fincas', 'rebanos', 'etapas',
            'animalId', 'fincaId', 'rebanoId', 'tipo', 'fechaInicio', 'fechaFin'
        ));
    }

    public function create(Request $request)
    {
        $animales = $this->filterFemaleAnimals($this->service->getAnimales());

        $fincasRes  = $this->fincasService->getFincas();
        $fincas     = ($fincasRes['success'] ?? false) ? ($fincasRes['data']['data'] ?? $fincasRes['data'] ?? []) : [];

        $rebanosRes = $this->rebanosService->getRebanos();
        $rebanos    = ($rebanosRes['success'] ?? false) ? ($rebanosRes['data']['data'] ?? $rebanosRes['data'] ?? []) : [];

        $etapasRes  = $this->etapaService->getAll();
        $etapas     = ($etapasRes['success'] ?? false) ? ($etapasRes['data']['data'] ?? $etapasRes['data'] ?? []) : [];

        $presetAnimalId = $request->query('animal_id');

        return view('reproduccion-animal.create', compact('animales', 'fincas', 'rebanos', 'etapas', 'presetAnimalId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha'       => 'required|date',
            'tipo'        => 'nullable|string|max:25',
            'observacion' => 'nullable|string|max:100',
            'animal_id'   => 'required|integer',
            'etapa_id'    => 'required|integer',
        ], [
            'fecha.required'     => 'La fecha de reproducción es requerida.',
            'animal_id.required' => 'El animal es requerido.',
            'etapa_id.required'  => 'La etapa del animal es requerida.',
        ]);

        $data = [
            'fecha_reproduccion' => $request->input('fecha'),
            'tipo_reproduccion'  => $request->input('tipo'),
            'observacion'        => $request->input('observacion'),
            'animal_id'          => $request->input('animal_id'),
            'etapa_id'           => $request->input('etapa_id'),
        ];

        $response = $this->service->create($data);

        if ($response['success'] ?? false) {
            return redirect()->route('reproduccion-animal.index')->with('success', 'Registro reproductivo guardado exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al crear el registro.');
    }

    public function show(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('reproduccion-animal.index')->with('error', 'Registro no encontrado.');
        }
        $reproduccion = $response['data'];

        $etapasRes  = $this->etapaService->getAll();
        $etapas     = ($etapasRes['success'] ?? false) ? ($etapasRes['data']['data'] ?? $etapasRes['data'] ?? []) : [];

        return view('reproduccion-animal.show', compact('reproduccion', 'etapas'));
    }

    public function edit(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('reproduccion-animal.index')->with('error', 'Registro no encontrado.');
        }
        $reproduccion = $response['data'];
        $animales     = $this->filterFemaleAnimals($this->service->getAnimales());

        $fincasRes  = $this->fincasService->getFincas(['incluir_archivados' => true]);
        $fincas     = ($fincasRes['success'] ?? false) ? ($fincasRes['data']['data'] ?? $fincasRes['data'] ?? []) : [];

        $rebanosRes = $this->rebanosService->getRebanos(['incluir_archivados' => true]);
        $rebanos    = ($rebanosRes['success'] ?? false) ? ($rebanosRes['data']['data'] ?? $rebanosRes['data'] ?? []) : [];

        $etapasRes  = $this->etapaService->getAll();
        $etapas     = ($etapasRes['success'] ?? false) ? ($etapasRes['data']['data'] ?? $etapasRes['data'] ?? []) : [];

        return view('reproduccion-animal.edit', compact('reproduccion', 'animales', 'fincas', 'rebanos', 'etapas'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'fecha'       => 'required|date',
            'tipo'        => 'nullable|string|max:25',
            'observacion' => 'nullable|string|max:100',
        ]);

        $data = [
            'fecha_reproduccion' => $request->input('fecha'),
            'tipo_reproduccion'  => $request->input('tipo'),
            'observacion'        => $request->input('observacion'),
        ];

        $response = $this->service->update($id, $data);
        if ($response['success'] ?? false) {
            return redirect()->route('reproduccion-animal.index')->with('success', 'Registro reproductivo actualizado exitosamente.');
        }
        return back()->withInput()->with('error', $response['message'] ?? 'Error al actualizar.');
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('reproduccion-animal.index')->with('success', 'Registro reproductivo eliminado exitosamente.');
        }
        return redirect()->route('reproduccion-animal.index')->with('error', $response['message'] ?? 'Error al eliminar.');
    }
}
