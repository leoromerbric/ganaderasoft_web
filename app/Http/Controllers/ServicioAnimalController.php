<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ServicioAnimalServiceInterface;
use App\Services\Contracts\FincasServiceInterface;
use App\Services\Contracts\RebanosServiceInterface;
use App\Services\Contracts\EtapaServiceInterface;
use Illuminate\Http\Request;

class ServicioAnimalController extends Controller
{
    public function __construct(
        protected ServicioAnimalServiceInterface $service,
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
        $tipoVal = data_get($persona, 'Tipo_Trabajador') ?? data_get($persona, 'tipo_trabajador') ?? data_get($persona, 'personal.Tipo_Trabajador') ?? '';
        if (is_array($tipoVal)) {
            $tipoVal = $tipoVal['nombre'] ?? $tipoVal['Nombre'] ?? '';
        }
        $tipo = strtolower(trim((string) $tipoVal));

        if ($tipo === '') {
            return true;
        }

        return str_contains($tipo, 'veterinario') || str_contains($tipo, 'tecnico') || str_contains($tipo, 'técnico');
    }

    private function filterVetTechStaff(array $personal): array
    {
        return array_values(array_filter($personal, fn (array $persona) => $this->isVetOrTech($persona)));
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

        $response  = $this->service->getList();
        $data      = ($response['success'] ?? false) ? ($response['data'] ?? []) : [];
        $servicios = (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) ? $data['data'] : $data;
        $animales  = $this->filterFemaleAnimals($this->service->getAnimales(['incluir_archivados' => true]));

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

        $etapasRes = $this->etapaService->getAll();
        $etapas = ($etapasRes['success'] ?? false) ? ($etapasRes['data']['data'] ?? $etapasRes['data'] ?? []) : [];

        return view('servicio-animal.index', compact(
            'servicios', 'animales', 'fincas', 'rebanos', 'etapas',
            'animalId', 'fincaId', 'rebanoId', 'tipo', 'fechaInicio', 'fechaFin'
        ));
    }

    public function create(Request $request)
    {
        $animales      = $this->filterFemaleAnimals($this->service->getAnimales());
        $semenToros    = $this->service->getSemenToros();
        $personal      = $this->filterVetTechStaff($this->service->getPersonalFinca());
        $registrosCelo = $this->service->getRegistrosCelo();

        $fincasRes = $this->fincasService->getFincas();
        $fincas = ($fincasRes['success'] ?? false) ? ($fincasRes['data']['data'] ?? $fincasRes['data'] ?? []) : [];

        $rebanosRes = $this->rebanosService->getRebanos();
        $rebanos = ($rebanosRes['success'] ?? false) ? ($rebanosRes['data']['data'] ?? $rebanosRes['data'] ?? []) : [];

        $etapasRes = $this->etapaService->getAll();
        $etapas = ($etapasRes['success'] ?? false) ? ($etapasRes['data']['data'] ?? $etapasRes['data'] ?? []) : [];

        $presetAnimalId = $request->query('animal_id');
        $presetCeloId   = $request->query('registro_celo_id');

        return view('servicio-animal.create', compact(
            'animales', 'semenToros', 'personal', 'registrosCelo',
            'fincas', 'rebanos', 'etapas', 'presetAnimalId', 'presetCeloId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'animal_id'   => 'required|integer',
            'tipo'        => 'nullable|string|max:25',
            'fecha'       => 'nullable|date',
            'observacion' => 'nullable|string|max:100',
        ], [
            'animal_id.required' => 'El animal es requerido.',
        ]);

        $data = [
            'animal_id'         => $request->input('animal_id'),
            'semen_toro_id'     => $request->input('semen_id') !== '' ? $request->input('semen_id') : null,
            'personal_finca_id' => $request->input('tecnico_id') !== '' ? $request->input('tecnico_id') : null,
            'registro_celo_id'  => $request->input('celo_id') !== '' ? $request->input('celo_id') : null,
            'tipo'              => $request->input('tipo'),
            'fecha'             => $request->input('fecha'),
            'observacion'       => $request->input('observacion'),
        ];

        $response = $this->service->create($data);

        if ($response['success'] ?? false) {
            return redirect()->route('servicio-animal.index')->with('success', 'Servicio reproductivo registrado exitosamente.');
        }
        return back()->withInput()->with('error', $this->apiMessage($response, 'Error al registrar el servicio.'));
    }

    public function show(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('servicio-animal.index')->with('error', 'Registro no encontrado.');
        }
        $servicio = $response['data'];

        $etapasRes = $this->etapaService->getAll();
        $etapas = ($etapasRes['success'] ?? false) ? ($etapasRes['data']['data'] ?? $etapasRes['data'] ?? []) : [];

        return view('servicio-animal.show', compact('servicio', 'etapas'));
    }

    public function edit(int $id)
    {
        $response = $this->service->getById($id);
        if (!($response['success'] ?? false)) {
            return redirect()->route('servicio-animal.index')->with('error', 'Registro no encontrado.');
        }
        $servicio      = $response['data'];
        $semenToros    = $this->service->getSemenToros();
        $personal      = $this->filterVetTechStaff($this->service->getPersonalFinca());
        $registrosCelo = $this->service->getRegistrosCelo();

        $etapasRes = $this->etapaService->getAll();
        $etapas = ($etapasRes['success'] ?? false) ? ($etapasRes['data']['data'] ?? $etapasRes['data'] ?? []) : [];

        return view('servicio-animal.edit', compact(
            'servicio', 'semenToros', 'personal', 'registrosCelo', 'etapas'
        ));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'tipo'        => 'nullable|string|max:25',
            'fecha'       => 'nullable|date',
            'observacion' => 'nullable|string|max:100',
        ]);

        $data = [
            'semen_toro_id'     => $request->input('semen_id') !== '' ? $request->input('semen_id') : null,
            'personal_finca_id' => $request->input('tecnico_id') !== '' ? $request->input('tecnico_id') : null,
            'registro_celo_id'  => $request->input('celo_id') !== '' ? $request->input('celo_id') : null,
            'tipo'              => $request->input('tipo'),
            'fecha'             => $request->input('fecha'),
            'observacion'       => $request->input('observacion'),
        ];

        $response = $this->service->update($id, $data);
        if ($response['success'] ?? false) {
            return redirect()->route('servicio-animal.index')->with('success', 'Servicio reproductivo actualizado exitosamente.');
        }
        return back()->withInput()->with('error', $this->apiMessage($response, 'Error al actualizar el servicio.'));
    }

    public function destroy(int $id)
    {
        $response = $this->service->eliminar($id);
        if ($response['success'] ?? false) {
            return redirect()->route('servicio-animal.index')->with('success', 'Servicio reproductivo eliminado exitosamente.');
        }
        return redirect()->route('servicio-animal.index')->with('error', $this->apiMessage($response, 'Error al eliminar.'));
    }
}
