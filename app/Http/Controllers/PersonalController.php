<?php

namespace App\Http\Controllers;

use App\Services\Contracts\PersonalServiceInterface;
use App\Services\Contracts\FincasServiceInterface;
use Illuminate\Http\Request;

class PersonalController extends Controller
{
    protected PersonalServiceInterface $personalService;
    protected FincasServiceInterface $fincasService;

    public function __construct(PersonalServiceInterface $personalService, FincasServiceInterface $fincasService)
    {
        $this->personalService = $personalService;
        $this->fincasService = $fincasService;
    }

    /**
     * Display list of personal for a finca
     */
    public function index(Request $request)
    {
        // Get list of fincas for dropdown
        $fincasResponse = $this->fincasService->getFincas();
        $fincas = ($fincasResponse['success'] ?? false) ? ($fincasResponse['data']['data'] ?? $fincasResponse['data'] ?? []) : [];

        // Check if there's a selected finca in session
        $selectedFinca = session('selected_finca');
        $idFinca = $request->query('id_finca') ?? ($selectedFinca['id'] ?? $selectedFinca['id_Finca'] ?? null);

        if (!$idFinca) {
            return view('personal.index', [
                'personal' => [],
                'pagination' => [],
                'fincas' => $fincas,
                'idFinca' => null,
                'error' => 'Debe seleccionar una finca para ver el personal'
            ]);
        }

        $response = $this->personalService->getPersonal((int)$idFinca);

        if (isset($response['success']) && $response['success']) {
            $personal = $response['data']['data'] ?? $response['data'] ?? [];
            $pagination = $response['pagination'] ?? [
                'current_page' => 1,
                'last_page' => 1,
                'total' => count($personal),
            ];

            return view('personal.index', compact('personal', 'pagination', 'fincas', 'idFinca'));
        }

        return view('personal.index', [
            'personal' => [],
            'pagination' => [],
            'fincas' => $fincas,
            'idFinca' => $idFinca,
            'error' => $response['message'] ?? 'Error al obtener el personal'
        ]);
    }

    /**
     * Show form to create new personal
     */
    public function create()
    {
        $selectedFinca = session('selected_finca');
        
        if (!$selectedFinca) {
            return redirect()->route('fincas.index')->with('error', 'Debe seleccionar una finca primero');
        }

        // Define employee types
        $tiposTrabajador = [
            'Administrador',
            'Tecnico',
            'Guardia',
            'Veterinario',
            'Operario',
            'Otro'
        ];

        return view('personal.create', compact('selectedFinca', 'tiposTrabajador'));
    }

    /**
     * Store new personal (API V2 payload format)
     */
    public function store(Request $request)
    {
        $selectedFinca = session('selected_finca');
        
        if (!$selectedFinca) {
            return redirect()->route('fincas.index')->with('error', 'Debe seleccionar una finca primero');
        }

        $fincaId = $selectedFinca['id'] ?? $selectedFinca['id_Finca'] ?? null;

        $data = [
            'finca_id' => $fincaId,
            'cedula' => (string)($request->input('Cedula') ?? $request->input('cedula')),
            'nombre' => $request->input('Nombre') ?? $request->input('nombre'),
            'apellido' => $request->input('Apellido') ?? $request->input('apellido'),
            'telefono' => $request->input('Telefono') ?? $request->input('telefono'),
            'correo' => $request->input('Correo') ?? $request->input('correo'),
            'Tipo_Trabajador' => $request->input('Tipo_Trabajador') ?? $request->input('tipo_trabajador'),
        ];

        $response = $this->personalService->createPersonal($data);

        if (isset($response['success']) && $response['success']) {
            return redirect()->route('personal.index')->with('success', 'Personal creado exitosamente');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $response['message'] ?? 'Error al crear el personal');
    }

    /**
     * Show form to edit existing personal
     */
    public function edit($id)
    {
        $selectedFinca = session('selected_finca');
        
        if (!$selectedFinca) {
            return redirect()->route('fincas.index')->with('error', 'Debe seleccionar una finca primero');
        }

        $fincaId = $selectedFinca['id'] ?? $selectedFinca['id_Finca'] ?? null;

        // Get all personal for current finca
        $response = $this->personalService->getPersonal((int)$fincaId);

        if (isset($response['success']) && $response['success']) {
            $allPersonal = $response['data']['data'] ?? $response['data'] ?? [];
            
            // Find persona by ID (V2 id vs V1 id_Tecnico)
            $persona = collect($allPersonal)->first(function ($p) use ($id) {
                $pId = $p['id'] ?? $p['id_Tecnico'] ?? null;
                return $pId == $id;
            });

            if ($persona) {
                $tiposTrabajador = [
                    'Administrador',
                    'Tecnico',
                    'Guardia',
                    'Veterinario',
                    'Operario',
                    'Otro'
                ];

                return view('personal.edit', compact('persona', 'selectedFinca', 'tiposTrabajador'));
            }

            return redirect()->route('personal.index')->with('error', 'Personal no encontrado');
        }

        return redirect()->route('personal.index')->with('error', $response['message'] ?? 'Error al obtener el personal');
    }

    /**
     * Update existing personal (API V2 payload format)
     */
    public function update(Request $request, $id)
    {
        $data = [
            'cedula' => (string)($request->input('Cedula') ?? $request->input('cedula')),
            'nombre' => $request->input('Nombre') ?? $request->input('nombre'),
            'apellido' => $request->input('Apellido') ?? $request->input('apellido'),
            'telefono' => $request->input('Telefono') ?? $request->input('telefono'),
            'correo' => $request->input('Correo') ?? $request->input('correo'),
            'Tipo_Trabajador' => $request->input('Tipo_Trabajador') ?? $request->input('tipo_trabajador'),
        ];

        $response = $this->personalService->updatePersonal($id, $data);

        if (isset($response['success']) && $response['success']) {
            return redirect()->route('personal.index')->with('success', 'Personal actualizado exitosamente');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $response['message'] ?? 'Error al actualizar el personal');
    }

    /**
     * API endpoint to get personal list
     */
    public function apiPersonal(Request $request)
    {
        $idFinca = $request->query('id_finca');

        if (!$idFinca) {
            return response()->json([
                'success' => false,
                'message' => 'Debe proporcionar el id_finca'
            ], 400);
        }

        $response = $this->personalService->getPersonal($idFinca);

        if (isset($response['success']) && $response['success']) {
            return response()->json($response);
        }

        return response()->json([
            'success' => false,
            'message' => $response['message'] ?? 'Error al obtener el personal'
        ], 500);
    }
}
