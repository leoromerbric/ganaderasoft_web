<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Middleware de verificación de roles del usuario en sesión.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! session('authenticated')) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para acceder.');
        }

        $userRoles = session('user.roles', []);

        // Administradores globales siempre tienen acceso
        if (in_array('global_admin', $userRoles, true) || in_array('admin', $userRoles, true)) {
            return $next($request);
        }

        foreach ($roles as $role) {
            if (in_array($role, $userRoles, true)) {
                return $next($request);
            }
        }

        return redirect()->route('profile')->with('error', 'No tiene el rol necesario para acceder a esta sección.');
    }
}
