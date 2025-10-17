<?php

namespace App\Http\Controllers;

use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthServiceInterface $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        // Redirect to dashboard if already authenticated
        if (session('authenticated')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login attempt
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $user = $this->authService->attempt($request->email, $request->password);

        if ($user) {
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son correctas.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        $this->authService->logout();

        return redirect()->route('login')->with('success', 'Sesión cerrada exitosamente.');
    }
}
