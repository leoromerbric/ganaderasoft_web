<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMockAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! session('authenticated')) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para acceder.');
        }

        // Si la cuenta del usuario está suspendida o inactiva, restringir navegación únicamente a su perfil
        $user = session('user');
        $status = strtolower($user['status'] ?? 'active');
        if (in_array($status, ['suspended', 'inactive', 'suspendido', 'inactivo'], true)) {
            if (!$request->routeIs('profile') && !$request->routeIs('logout')) {
                return redirect()->route('profile')->with('warning', 'Su cuenta se encuentra suspendida. Solo tiene acceso a la consulta de su perfil.');
            }
        }

        return $next($request);
    }
}
