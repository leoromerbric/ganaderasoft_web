<?php

namespace App\Http\Controllers;

use App\Services\Contracts\PersonalServiceInterface;
use Illuminate\Http\Request;

class PersonalController extends Controller
{
    protected PersonalServiceInterface $personalService;

    public function __construct(PersonalServiceInterface $personalService)
    {
        $this->personalService = $personalService;
    }

    /**
     * Display list of personal for a finca
     */
    public function index(Request $request)
    {
        // Check if there's a selected finca in session
        $selectedFinca = session('selected_finca');
        $idFinca = $request->query('id_finca') ?? ($selectedFinca['id_Finca'] ?? null);

        if (!$idFinca) {
            return view('personal.index', [
                'personal' => [],
                'pagination' => [],
                'error' => 'Debe seleccionar una finca primero'
            ]);
        }

        $response = $this->personalService->getPersonal($idFinca);

        if (isset($response['success']) && $response['success']) {
            $personal = $response['data'] ?? [];
            $pagination = $response['pagination'] ?? [
                'current_page' => 1,
                'last_page' => 1,
                'total' => count($personal),
            ];

            return view('personal.index', compact('personal', 'pagination', 'idFinca'));
        }

        return view('personal.index', [
            'personal' => [],
            'pagination' => [],
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
     * Store new personal
     */
    public function store(Request $request)
    {
        $selectedFinca = session('selected_finca');
        
        if (!$selectedFinca) {
            return redirect()->route('fincas.index')->with('error', 'Debe seleccionar una finca primero');
        }

        $data = [
            'id_Finca' => $selectedFinca['id_Finca'],
            'Cedula' => (int)$request->input('Cedula'),
            'Nombre' => $request->input('Nombre'),
            'Apellido' => $request->input('Apellido'),
            'Telefono' => $request->input('Telefono'),
            'Correo' => $request->input('Correo'),
            'Tipo_Trabajador' => $request->input('Tipo_Trabajador'),
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

        // Get all personal and find the one we need
        $response = $this->personalService->getPersonal($selectedFinca['id_Finca']);

        if (isset($response['success']) && $response['success']) {
            $allPersonal = $response['data'] ?? [];
            
            // Find the personal by ID
            $persona = collect($allPersonal)->firstWhere('id_Tecnico', (int)$id);

            if ($persona) {
                // Define employee types
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
     * Update existing personal
     */
    public function update(Request $request, $id)
    {
        $data = [
            'Cedula' => (int)$request->input('Cedula'),
            'Nombre' => $request->input('Nombre'),
            'Apellido' => $request->input('Apellido'),
            'Telefono' => $request->input('Telefono'),
            'Correo' => $request->input('Correo'),
            'Tipo_Trabajador' => $request->input('Tipo_Trabajador'),
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
