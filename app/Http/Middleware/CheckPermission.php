<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    // Middleware de verificación de permisos del usuario.
    
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (! session('authenticated')) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para acceder.');
        }

        $userRoles = session('user.roles', []);
        $userPermissions = session('user.permissions', []);

        // Administradores globales tienen acceso total
        if (in_array('admin', $userRoles, true) || in_array('global_admin', $userRoles, true)) {
            return $next($request);
        }

        // Verificar el permiso en la lista de la sesión
        if (! in_array($permission, $userPermissions, true)) {
            return redirect()->route('profile')->with('error', 'No tiene permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
