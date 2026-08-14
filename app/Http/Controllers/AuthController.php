<?php

namespace App\Http\Controllers;

use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

/**
 * Controlador para manejar la autenticación en el frontend (web y API proxy).
 */
class AuthController extends Controller
{
    /**
     * @var AuthServiceInterface
     */
    protected AuthServiceInterface $authService;

    /**
     * Inyecta el servicio de autenticación.
     *
     * @param AuthServiceInterface $authService
     */
    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Muestra el formulario de inicio de sesión.
     *
     * @return View|RedirectResponse
     */
    public function showLoginForm()
    {
        // Redirigir al dashboard si ya existe una sesión activa
        if (Session::has('authenticated')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Procesa el intento de inicio de sesión desde la web.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'El correo electrónico debe ser válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $user = $this->authService->login($request->email, $request->password);

        if ($user) {
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son correctas.',
        ])->withInput($request->only('email'));
    }

    /**
     * Cierra la sesión del usuario en la web.
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        $this->authService->logout();

        return redirect()->route('login')->with('success', 'Sesión cerrada exitosamente.');
    }

    /**
     * Endpoint API para iniciar sesión (usado como proxy por clientes externos si aplica).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function apiLogin(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = $this->authService->login($request->email, $request->password);

        if ($user) {
            $userProfile = $user;
            
            // Remover tokens del perfil del usuario para no duplicarlos en la respuesta JSON
            unset($userProfile['token'], $userProfile['token_type']);

            return response()->json([
                'success' => true,
                'message' => 'Login exitoso',
                'data'    => [
                    'user'       => $userProfile,
                    'token'      => $user['token'] ?? null,
                    'token_type' => $user['token_type'] ?? 'Bearer',
                ]
            ], Response::HTTP_OK);
        }

        return response()->json([
            'success' => false,
            'message' => 'Las credenciales proporcionadas no son correctas.'
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Show user profile card
     */
    public function profile()
    {
        $user = $this->authService->getProfile();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para ver su perfil.');
        }

        return view('auth.profile', compact('user'));
    }

    /**
     * Endpoint API para cerrar sesión.
     *
     * @return JsonResponse
     */
    public function apiLogout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente.'
        ], Response::HTTP_OK);
    }
}
