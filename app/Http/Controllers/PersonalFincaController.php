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
        $fincaId = $request->query('id_finca');
        
        $response = $this->personalFincaService->getPersonalFinca($fincaId);
        
        if (!$response['success']) {
            return redirect()->route('dashboard')->with('error', $response['message']);
        }

        // Get fincas for filter dropdown
        $fincasResponse = $this->fincasService->getFincas();
        $fincas = $fincasResponse['success'] ? ($fincasResponse['data']['data'] ?? []) : [];

        $personalFinca = $response['data'] ?? [];

        // Calculate statistics
        $estadisticas = [
            'total_personal' => count($personalFinca),
            'por_tipo' => [],
        ];

        // Count by tipo
        foreach ($personalFinca as $persona) {
            $tipo = $persona['Tipo_Trabajador'] ?? 'Sin especificar';
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
        $fincas = $fincasResponse['success'] ? ($fincasResponse['data']['data'] ?? []) : [];

        return view('personal-finca.create', compact('fincas'));
    }

    /**
     * Store a newly created personal de finca record
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_Finca' => 'required|integer',
            'Cedula' => 'required|min:7|max:12|regex:/^[0-9]+$/',
            'Nombre' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'Apellido' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'Telefono' => 'required|string|max:20|regex:/^[0-9\+\-\s\(\)]+$/',
            'Correo' => 'required|string|email|max:255',
            'Tipo_Trabajador' => 'required|string|in:Técnico,Veterinario,Operario,Vigilante,Supervisor,Administrador',
        ], [
            'id_Finca.required' => 'Debe seleccionar una finca.',
            'id_Finca.integer' => 'La finca seleccionada no es válida.',
            'Cedula.required' => 'La cédula es requerida.',
            'Cedula.min' => 'La cédula debe tener al menos 7 dígitos.',
            'Cedula.max' => 'La cédula no puede tener más de 12 dígitos.',
            'Cedula.regex' => 'La cédula debe contener solo números.',
            'Nombre.required' => 'El nombre es requerido.',
            'Nombre.max' => 'El nombre no puede exceder 100 caracteres.',
            'Nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'Apellido.required' => 'El apellido es requerido.',
            'Apellido.max' => 'El apellido no puede exceder 100 caracteres.',
            'Apellido.regex' => 'El apellido solo puede contener letras y espacios.',
            'Telefono.required' => 'El teléfono es requerido.',
            'Telefono.max' => 'El teléfono no puede exceder 20 caracteres.',
            'Telefono.regex' => 'El teléfono debe tener un formato válido.',
            'Correo.required' => 'El correo electrónico es requerido.',
            'Correo.email' => 'El correo electrónico debe tener un formato válido.',
            'Correo.max' => 'El correo electrónico no puede exceder 255 caracteres.',
            'Tipo_Trabajador.required' => 'Debe seleccionar el tipo de trabajador.',
            'Tipo_Trabajador.in' => 'El tipo de trabajador seleccionado no es válido.',
        ]);

        $data = $request->only([
            'id_Finca',
            'Cedula',
            'Nombre',
            'Apellido',
            'Telefono',
            'Correo',
            'Tipo_Trabajador'
        ]);

        $response = $this->personalFincaService->createPersonalFinca($data);

        if ($response['success']) {
            return redirect()->route('personal-finca.index')
                ->with('success', 'Personal de finca registrado exitosamente.');
        }

        return back()->withInput()->with('error', $response['message']);
    }

    /**
     * Display the specified personal de finca
     */
    public function show(string $id)
    {
        $response = $this->personalFincaService->getPersonalFincaById($id);

        if (!$response['success']) {
            return redirect()->route('personal-finca.index')->with('error', $response['message']);
        }

        $personalFinca = $response['data'];
        
        return view('personal-finca.show', compact('personalFinca'));
    }

    /**
     * Show the form for editing the specified personal de finca
     */
    public function edit(string $id)
    {
        $response = $this->personalFincaService->getPersonalFincaById($id);

        if (!$response['success']) {
            return redirect()->route('personal-finca.index')->with('error', $response['message']);
        }

        $personalFinca = $response['data'];

        $fincasResponse = $this->fincasService->getFincas();
        $fincas = $fincasResponse['success'] ? ($fincasResponse['data']['data'] ?? []) : [];

        return view('personal-finca.edit', compact('personalFinca', 'fincas'));
    }

    /**
     * Update the specified personal de finca record
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_Finca' => 'required|integer',
            'Cedula' => 'required|min:7|max:12|regex:/^[0-9]+$/',
            'Nombre' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'Apellido' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'Telefono' => 'required|string|max:20|regex:/^[0-9\+\-\s\(\)]+$/',
            'Correo' => 'required|string|email|max:255',
            'Tipo_Trabajador' => 'required|string|in:Técnico,Veterinario,Operario,Vigilante,Supervisor,Administrador',
        ], [
            'id_Finca.required' => 'Debe seleccionar una finca.',
            'id_Finca.integer' => 'La finca seleccionada no es válida.',
            'Cedula.required' => 'La cédula es requerida.',
            'Cedula.min' => 'La cédula debe tener al menos 7 dígitos.',
            'Cedula.max' => 'La cédula no puede tener más de 12 dígitos.',
            'Cedula.regex' => 'La cédula debe contener solo números.',
            'Nombre.required' => 'El nombre es requerido.',
            'Nombre.max' => 'El nombre no puede exceder 100 caracteres.',
            'Nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'Apellido.required' => 'El apellido es requerido.',
            'Apellido.max' => 'El apellido no puede exceder 100 caracteres.',
            'Apellido.regex' => 'El apellido solo puede contener letras y espacios.',
            'Telefono.required' => 'El teléfono es requerido.',
            'Telefono.max' => 'El teléfono no puede exceder 20 caracteres.',
            'Telefono.regex' => 'El teléfono debe tener un formato válido.',
            'Correo.required' => 'El correo electrónico es requerido.',
            'Correo.email' => 'El correo electrónico debe tener un formato válido.',
            'Correo.max' => 'El correo electrónico no puede exceder 255 caracteres.',
            'Tipo_Trabajador.required' => 'Debe seleccionar el tipo de trabajador.',
            'Tipo_Trabajador.in' => 'El tipo de trabajador seleccionado no es válido.',
        ]);

        $data = $request->only([
            'id_Finca',
            'Cedula',
            'Nombre',
            'Apellido',
            'Telefono',
            'Correo',
            'Tipo_Trabajador'
        ]);

        $response = $this->personalFincaService->updatePersonalFinca($id, $data);

        if ($response['success']) {
            return redirect()->route('personal-finca.index')
                ->with('success', 'Personal de finca actualizado exitosamente.');
        }

        return back()->withInput()->with('error', $response['message']);
    }

    /**
     * Remove the specified personal de finca record
     */
    public function destroy(string $id)
    {
        $response = $this->personalFincaService->deletePersonalFinca($id);

        if ($response['success']) {
            return redirect()->route('personal-finca.index')
                ->with('success', 'Personal de finca eliminado exitosamente.');
        }

        return redirect()->route('personal-finca.index')->with('error', $response['message']);
    }
}