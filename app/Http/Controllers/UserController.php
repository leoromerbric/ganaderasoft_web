<?php

namespace App\Http\Controllers;

use App\Services\Contracts\UserServiceInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserServiceInterface $userService;
    
    protected array $availableRoles = [
        ['code' => 'propietario', 'name' => 'Propietario de finca'],
        ['code' => 'global_admin', 'name' => 'Administrador del sistema'],
    ];

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Muestra la lista de usuarios.
     */
    public function index(Request $request)
    {
        $search = strtolower(trim($request->get('search', '')));
        $roleFilter = $request->get('role', '');
        $statusFilter = $request->get('status', '');

        $users = $this->userService->getUsers([
            'search' => $search,
            'role' => $roleFilter,
            'status' => $statusFilter,
        ]);

        return view('admin.users.index', compact('users', 'search', 'roleFilter', 'statusFilter'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        return view('admin.users.create', ['availableRoles' => $this->availableRoles]);
    }

    /**
     * Guarda un nuevo usuario.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'cedula' => 'required|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'password' => 'required|string|min:8',
            'roles' => 'required|array|min:1',
            'roles.*' => 'string|in:propietario,global_admin,admin',
        ]);

        $payload = [
            'nombre' => $validated['nombre'],
            'apellido' => $validated['apellido'],
            'correo' => $validated['correo'],
            'cedula' => $validated['cedula'],
            'telefono' => $validated['telefono'] ?? '',
            'password' => $validated['password'],
            'password_confirmation' => $validated['password'], // API requires password_confirmation
            'roles' => array_values(array_unique($validated['roles'])),
        ];

        $result = $this->userService->createUser($payload);

        if ($result['success']) {
            return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente.');
        }

        return back()->withInput()->with('error', $result['message'] ?? 'Error al crear el usuario.');
    }

    /**
     * Muestra el detalle completo de un usuario.
     */
    public function show($id)
    {
        $result = $this->userService->getUserById((int)$id);

        if (!$result['success']) {
            return redirect()->route('admin.users.index')->with('error', $result['message']);
        }

        $user = $result['data'];

        return view('admin.users.show', compact('user'));
    }

    /**
     * Muestra el formulario para editar un usuario.
     */
    public function edit($id)
    {
        $result = $this->userService->getUserById((int)$id);

        if (!$result['success']) {
            return redirect()->route('admin.users.index')->with('error', $result['message']);
        }

        $user = $result['data'];

        return view('admin.users.edit', [
            'user' => $user,
            'availableRoles' => $this->availableRoles,
        ]);
    }

    /**
     * Actualiza los datos de un usuario.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'cedula' => 'required|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:8',
            'roles' => 'required|array|min:1',
            'roles.*' => 'string|in:propietario,global_admin,admin',
            'status' => 'required|string|in:active,suspended',
        ]);

        $payload = [
            'nombre' => $validated['nombre'],
            'apellido' => $validated['apellido'],
            'correo' => $validated['correo'],
            'cedula' => $validated['cedula'],
            'telefono' => $validated['telefono'] ?? '',
            'roles' => array_values(array_unique($validated['roles'])),
            'status' => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $payload['password'] = $validated['password'];
            $payload['password_confirmation'] = $validated['password'];
        }

        $result = $this->userService->updateUser((int)$id, $payload);

        if ($result['success']) {
            return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado exitosamente.');
        }

        return back()->withInput()->with('error', $result['message'] ?? 'Error al actualizar el usuario.');
    }

    /**
     * Alterna el estado (Activo / Suspendido) de un usuario.
     */
    public function toggleStatus($id)
    {
        $result = $this->userService->toggleUserStatus((int)$id);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }
}
