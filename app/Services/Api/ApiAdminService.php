<?php

namespace App\Services\Api;

use App\Services\Contracts\AdminServiceInterface;

class ApiAdminService extends BaseApiService implements AdminServiceInterface
{
    /**
     * Extrae de forma segura el arreglo de elementos ignorando metadatos de paginación.
     */
    private function extractItems(array $response): array
    {
        $data = $response['data'] ?? [];
        if (isset($data['data']) && is_array($data['data']) && !isset($data['id'])) {
            return $data['data'];
        }
        return is_array($data) ? $data : [];
    }

    // Obtener métricas y KPIs globales para el Dashboard de Administración
    public function getDashboardKpis(): array
    {
        // 1. Obtener y procesar usuarios
        $usersResponse = $this->get('/users?nopaginate=true');
        $users = $this->extractItems($usersResponse);
        $usersCollection = collect($users);

        $totalUsers = $usersCollection->count();
        $recentUsers = $usersCollection->take(5)->values()->all();

        $suspendedUsers = $usersCollection->filter(function ($u) {
            if (!is_array($u)) return false;
            $st = strtolower($u['status'] ?? 'active');
            return in_array($st, ['suspended', 'inactive', 'suspendido', 'inactivo'], true);
        })->count();

        $activeUsers = $totalUsers - $suspendedUsers;

        $totalPropietarios = $usersCollection->filter(function ($u) {
            if (!is_array($u)) return false;
            $roles = collect($u['roles'] ?? [])->map(fn($r) => is_array($r) ? ($r['code'] ?? '') : (string)$r);
            return $roles->contains('propietario');
        })->count();

        $totalAdministradores = $usersCollection->filter(function ($u) {
            if (!is_array($u)) return false;
            $roles = collect($u['roles'] ?? [])->map(fn($r) => is_array($r) ? ($r['code'] ?? '') : (string)$r);
            return $roles->contains('admin') || $roles->contains('global_admin');
        })->count();

        // 2. Obtener total de fincas
        $fincasResponse = $this->get('/fincas?nopaginate=true');
        $totalFincas = count($this->extractItems($fincasResponse));

        // 3. Obtener total de rebaños
        $rebanosResponse = $this->get('/rebanos?nopaginate=true');
        $totalRebanos = count($this->extractItems($rebanosResponse));

        // 4. Obtener censo ganadero (animales)
        $animalesResponse = $this->get('/animales?nopaginate=true');
        $totalAnimales = count($this->extractItems($animalesResponse));

        return [
            'kpis' => [
                'total_users'           => $totalUsers,
                'active_users'          => $activeUsers,
                'suspended_users'       => $suspendedUsers,
                'total_fincas'          => $totalFincas,
                'total_rebanos'         => $totalRebanos,
                'total_animales'        => $totalAnimales,
                'total_propietarios'    => $totalPropietarios,
                'total_administradores' => $totalAdministradores,
            ],
            'recentUsers' => $recentUsers,
        ];
    }
}
