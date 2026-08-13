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
        
        $response = $this->personalFincaService->getPersonalFinca($fincaId ? (int)$fincaId : null);
        
        if (isset($response['success']) && !$response['success']) {
            return redirect()->route('dashboard')->with('error', $response['message'] ?? 'Error al obtener el personal');
        }

        // Get fincas for filter dropdown
        $fincasResponse = $this->fincasService->getFincas();
        $fincas = ($fincasResponse['success'] ?? false) ? ($fincasResponse['data']['data'] ?? $fincasResponse['data'] ?? []) : [];

        $personalFinca = $response['data']['data'] ?? $response['data'] ?? [];

        // Calculate statistics
        $estadisticas = [
            'total_personal' => count($personalFinca),
            'por_tipo' => [],
        ];

        // Count by tipo
        foreach ($personalFinca as $persona) {
            $tipo = $persona['tipo_trabajador']['nombre'] ?? 'Sin especificar';
            if (!isset($estadisticas['por_tipo'][$tipo])) {
                $estadisticas['por_tipo'][$tipo] = 0;
            }
            $estadisticas['por_tipo'][$tipo]++;
        }

        return view('personal-finca.index', compact('personalFinca', 'fincas', 'fincaId', 'estadisticas'));
    }

    /**
     * Show the form for creating new personal de finca
     */
    public function create()
    {
        $fincasResponse = $this->fincasService->getFincas();
        $fincas = ($fincasResponse['success'] ?? false) ? ($fincasResponse['data']['data'] ?? $fincasResponse['data'] ?? []) : [];

        return view('personal-finca.create', compact('fincas'));
    }

    /**
     * Store a newly created personal de finca record (API V2 payload format)
     */
    public function store(Request $request)
    {
        $request->validate([
            'finca_id' => 'required|integer',
            'cedula' => 'required|string|max:15',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'correo' => 'required|string|email|max:255',
            'tipo_trabajador' => 'required|string',
        ]);

        $data = [
            'finca_id' => (int)$request->input('finca_id'),
            'cedula' => (string)$request->input('cedula'),
            'nombre' => (string)$request->input('nombre'),
            'apellido' => (string)$request->input('apellido'),
            'telefono' => (string)$request->input('telefono'),
            'correo' => (string)$request->input('correo'),
            'tipo_trabajador' => (string)$request->input('tipo_trabajador'),
        ];

        $response = $this->personalFincaService->createPersonalFinca($data);

        if (isset($response['success']) && $response['success']) {
            return redirect()->route('personal-finca.index')
                ->with('success', 'Personal de finca registrado exitosamente.');
        }

        return back()->withInput()->with('error', $response['message'] ?? 'Error al crear el personal');
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

        return view('personal-finca.edit', compact('personalFinca', 'personal', 'fincas'));
    }

    /**
     * Update the specified personal de finca record (API V2 payload format)
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'finca_id' => 'required|integer',
            'cedula' => 'required|string|max:15',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'correo' => 'required|string|email|max:255',
            'tipo_trabajador' => 'required|string',
        ]);

        $data = [
            'finca_id' => (int)$request->input('finca_id'),
            'cedula' => (string)$request->input('cedula'),
            'nombre' => (string)$request->input('nombre'),
            'apellido' => (string)$request->input('apellido'),
            'telefono' => (string)$request->input('telefono'),
            'correo' => (string)$request->input('correo'),
            'tipo_trabajador' => (string)$request->input('tipo_trabajador'),
        ];

        $response = $this->personalFincaService->updatePersonalFinca((int)$id, $data);

        if (isset($response['success']) && $response['success']) {
            return redirect()->route('personal-finca.index')
                ->with('success', 'Personal de finca actualizado exitosamente.');
        }

        return back()->withInput()->with('error', $response['message'] ?? 'Error al actualizar el personal');
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