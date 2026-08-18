<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Directiva Blade: @haspermission('codigo.permiso')
        Blade::if('haspermission', function ($permission) {
            $userPermissions = session('user.permissions', []);
            $userRoles = session('user.roles', []);

            if (in_array('admin', $userRoles, true) || in_array('global_admin', $userRoles, true)) {
                return true;
            }

            return in_array($permission, $userPermissions, true);
        });

        // Directiva Blade: @hasrole('codigo_rol')
        Blade::if('hasrole', function ($roles) {
            $userRoles = session('user.roles', []);
            $checkRoles = is_array($roles) ? $roles : [$roles];
            return count(array_intersect($checkRoles, $userRoles)) > 0;
        });
    }
}
