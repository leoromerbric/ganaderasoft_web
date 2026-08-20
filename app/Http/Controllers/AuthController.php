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


    //Redirige al usuario según su rol hacia el dashboard correspondiente.

    private function redirectUserByRole(): RedirectResponse
    {
        $roles = session('user.roles', []);

        if (is_array($roles) && (in_array('global_admin', $roles, true) || in_array('admin', $roles, true))) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Muestra el formulario de inicio de sesión.
     *
     * @return View|RedirectResponse
     */
    public function showLoginForm()
    {
        // Redirigir al dashboard correspondiente si ya existe una sesión activa
        if (Session::has('authenticated')) {
            return $this->redirectUserByRole();
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
            return $this->redirectUserByRole();
        }

        $errorMessage = session('auth_error', 'Las credenciales proporcionadas no son correctas.');

        return back()->withErrors([
            'email' => $errorMessage,
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

    /**
     * Actualiza la foto de perfil del usuario autenticado.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'foto.required' => 'Debe seleccionar una imagen para su foto de perfil.',
            'foto.image'    => 'El archivo seleccionado debe ser una imagen válida.',
            'foto.mimes'    => 'La imagen debe tener formato: jpeg, png, jpg o webp.',
            'foto.max'      => 'El tamaño máximo permitido para la imagen es de 5MB.',
        ]);

        $response = $this->authService->updateProfilePhoto($request->file('foto'));

        if (!empty($response['success'])) {
            return redirect()->route('profile')
                ->with('success', '¡Foto de perfil actualizada exitosamente!');
        }

        return redirect()->route('profile')
            ->with('error', $response['message'] ?? 'Error al actualizar la foto de perfil.');
    }

    /**
     * Elimina la foto de perfil del usuario autenticado.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deletePhoto(Request $request)
    {
        $response = $this->authService->deleteProfilePhoto();

        if (!empty($response['success'])) {
            return redirect()->route('profile')
                ->with('success', 'Foto de perfil eliminada exitosamente.');
        }

        return redirect()->route('profile')
            ->with('error', $response['message'] ?? 'Error al eliminar la foto de perfil.');
    }
}
