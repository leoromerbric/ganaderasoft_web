@extends('layouts.authenticated')

@section('title', 'Mi Perfil')

@section('content')
<div class="max-w-6xl mx-auto space-y-4 sm:space-y-6">

    <!-- Header Section -->
    <div>
        <h1 class="text-xl sm:text-3xl font-bold text-ganaderasoft-negro">Mi Perfil</h1>
        <p class="text-gray-500 text-xs sm:text-sm mt-0.5">Gestión de datos de usuario e información personal.</p>
    </div>

    @if(session('warning'))
        <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 sm:px-5 sm:py-4 rounded-xl sm:rounded-2xl flex items-center space-x-3 shadow-sm" role="alert">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <p class="text-xs sm:text-sm font-bold">Acceso restringido</p>
                <p class="text-[11px] sm:text-xs text-amber-700 mt-0.5">{{ session('warning') }}</p>
            </div>
        </div>
    @endif

    @php
        $statusStr = strtolower($user['status'] ?? 'active');
        $isSuspended = in_array($statusStr, ['suspended', 'inactive', 'suspendido', 'inactivo'], true);
    @endphp

    @if($isSuspended)
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 sm:p-5 rounded-xl sm:rounded-2xl flex items-start space-x-3 sm:space-x-4 shadow-sm">
            <div class="p-1.5 sm:p-2 rounded-lg sm:rounded-xl bg-rose-100 text-rose-600 shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
            <div class="space-y-0.5 sm:space-y-1">
                <h3 class="text-sm sm:text-base font-bold text-rose-900">Cuenta suspendida</h3>
                <p class="text-xs text-rose-700 leading-relaxed">
                    Tu cuenta de usuario se encuentra suspendida temporalmente. No tienes acceso a los módulos operativos de la plataforma (rebaños, fincas, producción, reportes). Contacta a la administración de la Facultad de Agronomía para reactivar tus accesos.
                </p>
            </div>
        </div>
    @endif

    <!-- Main Profile Hero Card -->
    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
        <!-- Hero Gradient Banner -->
        <div class="h-24 sm:h-36 bg-gradient-to-r from-ganaderasoft-azul via-[#006073] to-ganaderasoft-celeste relative">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" width="100%" height="100%" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <pattern id="hero-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                        <circle cx="20" cy="20" r="2" fill="white" />
                        <path d="M0 40L40 0M0 0l40 40" stroke="white" stroke-width="0.5" />
                    </pattern>
                    <rect width="100%" height="100%" fill="url(#hero-pattern)" />
                </svg>
            </div>
        </div>

        <!-- Identity Bar (Avatar + Name + Roles) -->
        <div class="px-4 sm:px-8 pb-4 sm:pb-6 bg-white">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-3 sm:gap-4 -mt-10 sm:-mt-14 mb-3 sm:mb-4">
                <!-- Avatar with Explicit Fixed Dimensions -->
                <div class="relative w-20 h-20 sm:w-28 sm:h-28 shrink-0">
                    <div class="w-full h-full rounded-xl sm:rounded-2xl bg-white p-1 sm:p-1.5 shadow-xl ring-4 ring-white border border-gray-100 flex items-center justify-center overflow-hidden">
                        @php
                            $userImage = $user['avatar'] ?? $user['image'] ?? $user['profile_photo_url'] ?? null;
                            $hasImage = !empty($userImage) && (filter_var($userImage, FILTER_VALIDATE_URL) || str_starts_with($userImage, 'http') || str_starts_with($userImage, '/') || str_starts_with($userImage, 'data:image'));
                        @endphp

                        @if($hasImage)
                            <img src="{{ $userImage }}" 
                                 alt="{{ $user['name'] ?? 'Usuario' }}"     
                                 class="w-full h-full rounded-lg sm:rounded-xl object-cover"
                                 onerror="this.style.display='none'; document.getElementById('avatar-fallback').classList.remove('hidden');">
                        @endif

                        <div id="avatar-fallback" class="w-full h-full rounded-lg sm:rounded-xl bg-gradient-to-br from-ganaderasoft-azul to-ganaderasoft-celeste flex items-center justify-center text-white text-2xl sm:text-4xl font-extrabold shadow-inner {{ $hasImage ? 'hidden' : '' }}">
                            {{ strtoupper(substr($user['name'] ?? 'U', 0, 1)) }}
                        </div>
                    </div>
                    <span class="absolute bottom-0.5 right-0.5 sm:bottom-1 sm:right-1 w-4 h-4 sm:w-5 sm:h-5 {{ $isSuspended ? 'bg-rose-500' : 'bg-emerald-500' }} border-2 border-white rounded-full shadow-sm" title="{{ $isSuspended ? 'Cuenta suspendida' : 'Usuario activo' }}"></span>
                </div>
            </div>

            <!-- User Info -->
            <div class="space-y-1">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">{{ $user['name'] ?? 'Usuario' }}</h2>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-medium capitalize {{ $isSuspended ? 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/20' : 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20' }}">
                        {{ $isSuspended ? 'Suspendido' : ucfirst($user['status'] ?? 'activo') }}
                    </span>
                </div>

                <div class="flex flex-wrap items-center gap-y-1 gap-x-3 sm:gap-x-4 text-xs sm:text-sm text-gray-500">
                    <span class="flex items-center text-gray-600">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 text-ganaderasoft-azul shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        {{ $user['email'] ?? 'No especificado' }}
                    </span>
                    @if(!empty($user['persona']['cedula']))
                        <span class="hidden sm:inline text-gray-300">•</span>
                        <span class="flex items-center text-gray-500">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3 3 0 00-3 3b2 2 0 002 2h6a2 2 0 002-2 3 3 0 00-3-3H9z" />
                            </svg>
                            Cédula: {{ $user['persona']['cedula'] }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid (3 Stat Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
        <!-- Stat 1: Account Status -->
        <div class="bg-white p-3.5 sm:p-5 rounded-xl sm:rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex items-center space-x-3 sm:space-x-4">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl {{ $isSuspended ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }} flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-gray-400">Estado Cuenta</p>
                <p class="text-sm sm:text-lg font-bold {{ $isSuspended ? 'text-rose-600' : 'text-emerald-600' }} capitalize">{{ $isSuspended ? 'Suspendido' : ucfirst($user['status'] ?? 'Activo') }}</p>
            </div>
        </div>

        <!-- Stat 2: Primary Role -->
        <div class="bg-white p-3.5 sm:p-5 rounded-xl sm:rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex items-center space-x-3 sm:space-x-4">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-ganaderasoft-azul shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-gray-400">Rol Principal</p>
                <p class="text-sm sm:text-lg font-bold text-gray-900 truncate">
                    @if(!empty($user['roles']))
                        {{ ucfirst(str_replace('_', ' ', $user['roles'][0])) }}
                    @else
                        {{ ucfirst($user['type_user'] ?? 'Usuario') }}
                    @endif
                </p>
            </div>
        </div>

        <!-- Stat 3: Member Since -->
        <div class="bg-white p-3.5 sm:p-5 rounded-xl sm:rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex items-center space-x-3 sm:space-x-4">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-gray-400">Miembro Desde</p>
                <p class="text-sm sm:text-lg font-bold text-gray-900">
                    {{ isset($user['created_at']) ? \Carbon\Carbon::parse($user['created_at'])->format('d M, Y') : 'N/A' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Tabbed Navigation Container -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="border-b border-gray-100 bg-gray-50/50 px-4 sm:px-6 pt-2 sm:pt-3">
            <nav class="flex space-x-3 sm:space-x-6" aria-label="Tabs">
                <button type="button" 
                    id="tab-btn-general" 
                    onclick="switchTab('general')"
                    class="profile-tab-btn flex items-center py-2.5 sm:py-3.5 px-1 border-b-2 font-semibold text-xs sm:text-sm transition-all border-ganaderasoft-azul text-ganaderasoft-azul">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Información General
                </button>

                <button type="button" 
                    id="tab-btn-roles" 
                    onclick="switchTab('roles')"
                    class="profile-tab-btn flex items-center py-2.5 sm:py-3.5 px-1 border-b-2 font-semibold text-xs sm:text-sm transition-all border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    Roles Asignados
                </button>
            </nav>
        </div>

        <!-- Tab Contents Body -->
        <div class="p-4 sm:p-6 md:p-8">
            
            <!-- TAB 1: INFORMACIÓN GENERAL -->
            <div id="tab-content-general" class="profile-tab-content space-y-4 sm:space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    
                    <!-- Card: Datos de la Cuenta -->
                    <div class="bg-gray-50/80 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-gray-200/70 hover:border-ganaderasoft-azul/30 transition-all space-y-3 sm:space-y-4">
                        <div class="flex items-center space-x-2.5 sm:space-x-3 pb-2.5 sm:pb-3 border-b border-gray-200/80">
                            <div class="p-1.5 sm:p-2 rounded-lg sm:rounded-xl bg-ganaderasoft-azul/10 text-ganaderasoft-azul shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-sm sm:text-base">Datos del Usuario</h3>
                                <p class="text-[10px] sm:text-xs text-gray-500">Credenciales del sistema</p>
                            </div>
                        </div>

                        <div class="space-y-2.5 sm:space-y-3 text-xs sm:text-sm">
                            <div class="flex flex-col min-[480px]:flex-row min-[480px]:items-center min-[480px]:justify-between py-1.5 sm:py-2 border-b border-gray-200/50 gap-0.5 min-[480px]:gap-4">
                                <span class="text-gray-500 font-medium text-xs shrink-0">Nombre de Usuario:</span>
                                <span class="font-semibold text-gray-900 text-xs sm:text-sm text-left min-[480px]:text-right">{{ $user['name'] ?? 'N/A' }}</span>
                            </div>

                            <div class="flex flex-col min-[480px]:flex-row min-[480px]:items-center min-[480px]:justify-between py-1.5 sm:py-2 border-b border-gray-200/50 gap-0.5 min-[480px]:gap-4">
                                <span class="text-gray-500 font-medium text-xs shrink-0">Correo Electrónico:</span>
                                <span class="font-semibold text-ganaderasoft-azul text-xs sm:text-sm text-left min-[480px]:text-right break-all min-[480px]:break-normal">{{ $user['email'] ?? 'N/A' }}</span>
                            </div>

                            <div class="flex flex-col min-[480px]:flex-row min-[480px]:items-center min-[480px]:justify-between py-1.5 sm:py-2 border-b border-gray-200/50 gap-0.5 min-[480px]:gap-4">
                                <span class="text-gray-500 font-medium text-xs shrink-0">Fecha de Registro:</span>
                                <span class="font-semibold text-gray-800 text-xs sm:text-sm text-left min-[480px]:text-right">
                                    {{ isset($user['created_at']) ? \Carbon\Carbon::parse($user['created_at'])->format('d/m/Y H:i') : 'N/A' }}
                                </span>
                            </div>

                            <div class="flex flex-col min-[480px]:flex-row min-[480px]:items-center min-[480px]:justify-between py-1.5 sm:py-2 gap-1 min-[480px]:gap-4">
                                <span class="text-gray-500 font-medium text-xs shrink-0">Estado del Sistema:</span>
                                <span class="inline-flex items-center w-fit px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 mr-1.5"></span>
                                    {{ ucfirst($user['status'] ?? 'activo') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Card: Datos Personales -->
                    <div class="bg-gray-50/80 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-gray-200/70 hover:border-ganaderasoft-azul/30 transition-all space-y-3 sm:space-y-4">
                        <div class="flex items-center space-x-2.5 sm:space-x-3 pb-2.5 sm:pb-3 border-b border-gray-200/80">
                            <div class="p-1.5 sm:p-2 rounded-lg sm:rounded-xl bg-ganaderasoft-celeste/20 text-ganaderasoft-azul shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3 3 0 00-3 3b2 2 0 002 2h6a2 2 0 002-2 3 3 0 00-3-3H9z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-sm sm:text-base">Datos Personales</h3>
                                <p class="text-[10px] sm:text-xs text-gray-500">Información de identidad física</p>
                            </div>
                        </div>

                        <div class="space-y-2.5 sm:space-y-3 text-xs sm:text-sm">
                            @if(!empty($user['persona']))
                                @php $persona = $user['persona']; @endphp
                                <div class="flex flex-col min-[480px]:flex-row min-[480px]:items-center min-[480px]:justify-between py-1.5 sm:py-2 border-b border-gray-200/50 gap-0.5 min-[480px]:gap-4">
                                    <span class="text-gray-500 font-medium text-xs shrink-0">Cédula de Identidad:</span>
                                    <span class="font-semibold text-gray-900 font-mono text-xs sm:text-sm text-left min-[480px]:text-right">{{ $persona['cedula'] ?? 'N/A' }}</span>
                                </div>

                                <div class="flex flex-col min-[480px]:flex-row min-[480px]:items-center min-[480px]:justify-between py-1.5 sm:py-2 border-b border-gray-200/50 gap-0.5 min-[480px]:gap-4">
                                    <span class="text-gray-500 font-medium text-xs shrink-0">Nombre Completo:</span>
                                    <span class="font-semibold text-gray-900 text-xs sm:text-sm text-left min-[480px]:text-right">{{ trim(($persona['nombre'] ?? '') . ' ' . ($persona['apellido'] ?? '')) ?: 'N/A' }}</span>
                                </div>

                                <div class="flex flex-col min-[480px]:flex-row min-[480px]:items-center min-[480px]:justify-between py-1.5 sm:py-2 border-b border-gray-200/50 gap-0.5 min-[480px]:gap-4">
                                    <span class="text-gray-500 font-medium text-xs shrink-0">Teléfono Móvil / Contacto:</span>
                                    <span class="font-semibold text-gray-900 text-xs sm:text-sm text-left min-[480px]:text-right">{{ $persona['telefono'] ?? 'No registrado' }}</span>
                                </div>

                                <div class="flex flex-col min-[480px]:flex-row min-[480px]:items-center min-[480px]:justify-between py-1.5 sm:py-2 gap-1 min-[480px]:gap-4">
                                    <span class="text-gray-500 font-medium text-xs shrink-0">Estado de Registro:</span>
                                    <span class="inline-flex items-center w-fit px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Registrado
                                    </span>
                                </div>
                            @else
                                <div class="py-6 sm:py-8 text-center space-y-2 sm:space-y-3">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-amber-50 text-amber-500 mx-auto flex items-center justify-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-500 italic max-w-xs mx-auto">
                                        No se han encontrado datos personales registrados para este usuario.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            <!-- TAB 2: ROLES -->
            <div id="tab-content-roles" class="profile-tab-content space-y-4 sm:space-y-6 hidden">
                <div class="space-y-3 sm:space-y-4">
                    <div>
                        <h3 class="font-bold text-gray-900 text-base sm:text-lg">Roles Asignados</h3>
                        <p class="text-[10px] sm:text-xs text-gray-500">Roles activos configurados para este usuario.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                        @if(!empty($user['roles']))
                            @foreach($user['roles'] as $role)
                                <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 border border-gray-200 shadow-sm space-y-2.5 sm:space-y-3 hover:border-ganaderasoft-azul/40 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="p-1.5 sm:p-2 rounded-lg sm:rounded-xl bg-ganaderasoft-azul/10 text-ganaderasoft-azul">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                            </svg>
                                        </div>
                                        <span class="text-[10px] sm:text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Activo
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-sm sm:text-base capitalize">{{ str_replace('_', ' ', $role) }}</h4>
                                        <p class="text-[10px] sm:text-xs text-gray-500 mt-0.5">Rol activo en el sistema.</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 border border-gray-200 shadow-sm space-y-2.5 sm:space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="p-1.5 sm:p-2 rounded-lg sm:rounded-xl bg-gray-100 text-gray-600">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <span class="text-[10px] sm:text-xs font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                        Estándar
                                    </span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm sm:text-base">{{ ucfirst($user['type_user'] ?? 'Usuario General') }}</h4>
                                    <p class="text-[10px] sm:text-xs text-gray-500 mt-0.5">Acceso estándar al sistema.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchTab(tabName) {
        document.querySelectorAll('.profile-tab-content').forEach(el => {
            el.classList.add('hidden');
        });

        document.querySelectorAll('.profile-tab-btn').forEach(btn => {
            btn.classList.remove('border-ganaderasoft-azul', 'text-ganaderasoft-azul');
            btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        });

        const targetContent = document.getElementById('tab-content-' + tabName);
        if (targetContent) {
            targetContent.classList.remove('hidden');
        }

        const targetBtn = document.getElementById('tab-btn-' + tabName);
        if (targetBtn) {
            targetBtn.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            targetBtn.classList.add('border-ganaderasoft-azul', 'text-ganaderasoft-azul');
        }
    }
</script>
@endpush