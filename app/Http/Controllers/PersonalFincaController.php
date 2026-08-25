<?php

namespace App\Http\Controllers;

use App\Services\Contracts\PersonalFincaServiceInterface;
use App\Services\Contracts\FincasServiceInterface;
use Illuminate\Http\Request;

class PersonalFincaController extends Controller
{
    protected PersonalFincaServiceInterface $personalFincaService;
    protected FincasServiceInterface $fincasService;

    public function __construct(
        PersonalFincaServiceInterface $personalFincaService,
        FincasServiceInterface $fincasService
    ) {
        $this->personalFincaService = $personalFincaService;
        $this->fincasService = $fincasService;
    }

    /**
     * Display a listing of personal de finca
     */
    public function index(Request $request)
    {
        $fincaId = $request->query('finca_id') ?? $request->query('id_finca');
        
        $response = $this->personalFincaService->getPersonalFinca();
        
        if (isset($response['success']) && !$response['success']) {
            return redirect()->route('dashboard')->with('error', $response['message'] ?? 'Error al obtener el personal');
        }

        // Get fincas for filter dropdown
        $fincasResponse = $this->fincasService->getFincas();
        $fincas = ($fincasResponse['success'] ?? false) ? ($fincasResponse['data']['data'] ?? $fincasResponse['data'] ?? []) : [];

        // Get tipos de trabajador from backend API
        $tiposResponse = $this->personalFincaService->getTiposTrabajador();
        $tiposTrabajador = ($tiposResponse['success'] ?? false) ? ($tiposResponse['data']['data'] ?? $tiposResponse['data'] ?? []) : [];

        $personalFinca = $response['data']['data'] ?? $response['data'] ?? [];

        // Calculate statistics
        $totalPersonal = count($personalFinca);
        $personalActivo = count(array_filter($personalFinca, fn($p) => ($p['status'] ?? true) == true));
        $fincasConPersonal = count(array_unique(array_filter(array_map(fn($p) => $p['finca_id'] ?? data_get($p, 'finca.id'), $personalFinca))));
        $totalTipos = count($tiposTrabajador);

        $estadisticas = [
            'total_personal' => $totalPersonal,
            'personal_activo' => $personalActivo,
            'fincas_con_personal' => $fincasConPersonal,
            'total_tipos' => $totalTipos,
        ];

        return view('personal-finca.index', compact('personalFinca', 'fincas', 'fincaId', 'estadisticas', 'tiposTrabajador'));
    }

    /**
     * Show the form for creating new personal de finca
     */
    public function create()
    {
        $fincasResponse = $this->fincasService->getFincas();
        $fincas = ($fincasResponse['success'] ?? false) ? ($fincasResponse['data']['data'] ?? $fincasResponse['data'] ?? []) : [];

        $tiposResponse = $this->personalFincaService->getTiposTrabajador();
        $tiposTrabajador = ($tiposResponse['success'] ?? false) ? ($tiposResponse['data']['data'] ?? $tiposResponse['data'] ?? []) : [];

        return view('personal-finca.create', compact('fincas', 'tiposTrabajador'));
    }

    /**
     * Store a newly created personal de finca record (API V2 payload format)
     */
    public function store(Request $request)
    {
        $request->validate([
            'finca_id' => 'required|integer',
            'cedula' => 'required|string|regex:/^[VEJPG][0-9]+$/',
            'nombre' => 'required|string|max:25',
            'apellido' => 'required|string|max:25',
            'telefono' => 'required|string|max:15',
            'correo' => 'required|string|email|max:40',
            'fecha_nacimiento' => 'nullable|date',
            'tipo_trabajador_id' => 'required|integer',
        ], [
            'cedula.regex' => 'La cédula debe comenzar con V, E, J, P o G seguido de números (ej: V12345678).',
            'tipo_trabajador_id.required' => 'Debe seleccionar un tipo de trabajador.',
        ]);

        $data = [
            'finca_id' => (int)$request->input('finca_id'),
            'cedula' => strtoupper(trim((string)$request->input('cedula'))),
            'nombre' => trim((string)$request->input('nombre')),
            'apellido' => trim((string)$request->input('apellido')),
            'telefono' => trim((string)$request->input('telefono')),
            'correo' => trim((string)$request->input('correo')),
            'fecha_nacimiento' => $request->input('fecha_nacimiento') ?: null,
            'tipo_trabajador_id' => (int)$request->input('tipo_trabajador_id'),
        ];

        $response = $this->personalFincaService->createPersonalFinca($data);

        if (isset($response['success']) && $response['success']) {
            return redirect()->route('personal-finca.index')
                ->with('success', 'Personal de finca registrado exitosamente.');
        }

        $errorMessage = $response['message'] ?? 'Error al crear el personal';
        if (isset($response['errors']) && is_array($response['errors'])) {
            $errorMessage = implode(' ', array_map(function ($err) {
                return is_array($err) ? implode(' ', $err) : $err;
            }, $response['errors']));
        }

        return back()->withInput()->with('error', $errorMessage);
    }

    /**
     * Display the specified personal de finca
     */
    public function show(string $id)
    {
        $response = $this->personalFincaService->getPersonalFincaById((int)$id);

        if (!isset($response['success']) || !$response['success']) {
            return redirect()->route('personal-finca.index')->with('error', $response['message'] ?? 'Personal no encontrado');
        }

        $personalFinca = $response['data'] ?? [];
        $personal = $personalFinca;

        return view('personal-finca.show', compact('personalFinca', 'personal'));
    }

    /**
     * Show the form for editing the specified personal de finca
     */
    public function edit(string $id)
    {
        $response = $this->personalFincaService->getPersonalFincaById((int)$id);

        if (!isset($response['success']) || !$response['success']) {
            return redirect()->route('personal-finca.index')->with('error', $response['message'] ?? 'Personal no encontrado');
        }

        $personalFinca = $response['data'] ?? [];
        $personal = $personalFinca;

        $fincasResponse = $this->fincasService->getFincas();
        $fincas = ($fincasResponse['success'] ?? false) ? ($fincasResponse['data']['data'] ?? $fincasResponse['data'] ?? []) : [];

        $tiposResponse = $this->personalFincaService->getTiposTrabajador();
        $tiposTrabajador = ($tiposResponse['success'] ?? false) ? ($tiposResponse['data']['data'] ?? $tiposResponse['data'] ?? []) : [];

        return view('personal-finca.edit', compact('personalFinca', 'personal', 'fincas', 'tiposTrabajador'));
    }

    /**
     * Update the specified personal de finca record (API V2 payload format)
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'finca_id' => 'required|integer',
            'cedula' => 'required|string|regex:/^[VEJPG][0-9]+$/',
            'nombre' => 'required|string|max:25',
            'apellido' => 'required|string|max:25',
            'telefono' => 'required|string|max:15',
            'correo' => 'required|string|email|max:40',
            'fecha_nacimiento' => 'nullable|date',
            'tipo_trabajador_id' => 'required|integer',
        ], [
            'cedula.regex' => 'La cédula debe comenzar con V, E, J, P o G seguido de números (ej: V12345678).',
            'tipo_trabajador_id.required' => 'Debe seleccionar un tipo de trabajador.',
        ]);

        $data = [
            'finca_id' => (int)$request->input('finca_id'),
            'cedula' => strtoupper(trim((string)$request->input('cedula'))),
            'nombre' => trim((string)$request->input('nombre')),
            'apellido' => trim((string)$request->input('apellido')),
            'telefono' => trim((string)$request->input('telefono')),
            'correo' => trim((string)$request->input('correo')),
            'fecha_nacimiento' => $request->input('fecha_nacimiento') ?: null,
            'tipo_trabajador_id' => (int)$request->input('tipo_trabajador_id'),
        ];

        $response = $this->personalFincaService->updatePersonalFinca((int)$id, $data);

        if (isset($response['success']) && $response['success']) {
            return redirect()->route('personal-finca.index')
                ->with('success', 'Personal de finca actualizado exitosamente.');
        }

        $errorMessage = $response['message'] ?? 'Error al actualizar el personal';
        if (isset($response['errors']) && is_array($response['errors'])) {
            $errorMessage = implode(' ', array_map(function ($err) {
                return is_array($err) ? implode(' ', $err) : $err;
            }, $response['errors']));
        }

        return back()->withInput()->with('error', $errorMessage);
    }

    /**
     * Remove the specified personal de finca record
     */
    public function destroy(string $id)
    {
        $response = $this->personalFincaService->deletePersonalFinca((int)$id);

        if (isset($response['success']) && $response['success']) {
            return redirect()->route('personal-finca.index')
                ->with('success', 'Personal de finca eliminado exitosamente.');
        }

        return redirect()->route('personal-finca.index')->with('error', $response['message'] ?? 'Error al eliminar el personal');
    }
}