@extends('layouts.public')

@section('title', '404 - Página no encontrada | GanaderaSoft')

@php
    $isAuthenticated = (bool) session('authenticated', false);
    $roles = session('user.roles', []);
    $isAdmin = is_array($roles) && (in_array('global_admin', $roles, true) || in_array('admin', $roles, true));
    $dashboardUrl = $isAdmin ? route('admin.dashboard') : route('dashboard');
@endphp

@section('main-content')
    <!-- Cuadrícula SVG decorativa de fondo continuo -->
    <div class="absolute inset-0 pointer-events-none opacity-25 z-0">
        <svg class="w-full h-full" width="100%" height="100%" fill="none">
            <defs>
                <pattern id="grid-pattern-404" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#007B92" stroke-width="0.75" stroke-dasharray="2,2"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid-pattern-404)" />
        </svg>
    </div>

    <!-- Orbes difusos de luz ambiental -->
    <div class="absolute -top-32 -left-32 w-96 h-96 sm:w-[32rem] sm:h-[32rem] bg-ganaderasoft-celeste/25 rounded-full blur-3xl pointer-events-none z-0"></div>
    <div class="absolute top-1/2 -right-32 w-96 h-96 sm:w-[32rem] sm:h-[32rem] bg-ganaderasoft-azul/20 rounded-full blur-3xl pointer-events-none z-0"></div>
    <div class="absolute -bottom-32 left-1/3 w-96 h-96 sm:w-[32rem] sm:h-[32rem] bg-ganaderasoft-verde/20 rounded-full blur-3xl pointer-events-none z-0"></div>

    <!-- CONTENIDO CENTRAL 404 (A Pantalla Completa y Centrado) -->
    <div class="flex-grow flex items-center justify-center relative z-10 py-10 sm:py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full mx-auto text-center space-y-5 sm:space-y-7">
            
            <!-- Badge 404 -->
            <div>
                <span class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-rose-50 border border-rose-200/80 text-rose-600 text-xs sm:text-sm font-bold shadow-xs">
                    <span class="flex h-2 w-2 rounded-full bg-rose-500 animate-ping"></span>
                    <span>Error 404 &bull; {{ $isAuthenticated ? 'Ruta fuera del rebaño' : 'Página no encontrada' }}</span>
                </span>
            </div>

            <!-- Número 404 Grande y Nítido -->
            <div class="select-none leading-none my-2 sm:my-3">
                <span class="font-black tracking-tight text-ganaderasoft-azul leading-none block drop-shadow-xs" style="font-size: clamp(5rem, 15vw, 9rem); line-height: 1;">
                    404
                </span>
            </div>

            <!-- Título y Mensaje Totalmente Abierto y Espacioso -->
            <div class="space-y-3 sm:space-y-4 max-w-3xl mx-auto px-4">
                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-ganaderasoft-negro tracking-tight leading-tight">
                    @if($isAuthenticated)
                        Esta página no se encuentra en el potrero
                    @else
                        Esta página no existe o ha sido movida
                    @endif
                </h1>

                <p class="text-sm sm:text-lg text-gray-600 font-normal leading-relaxed max-w-2xl mx-auto">
                    @if($isAuthenticated)
                        La dirección a la que intentas acceder no está disponible o el registro fue reubicado. Puedes volver directamente a tu panel de control.
                    @else
                        La dirección web ingresada no coincide con ninguna ruta disponible. Si formas parte de la facultad, inicia sesión para ingresar a la plataforma.
                    @endif
                </p>
            </div>

            <!-- Botones de Acción -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 pt-2 max-w-md mx-auto w-full">
                @if($isAuthenticated)
                    <a href="{{ $dashboardUrl }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 sm:px-8 sm:py-4 text-xs sm:text-sm font-bold text-white bg-gradient-to-r from-ganaderasoft-celeste via-[#007B92] to-ganaderasoft-azul rounded-2xl shadow-xl shadow-ganaderasoft-azul/25 hover:shadow-2xl hover:scale-105 transition-all duration-300">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Ir al dashboard
                    </a>

                    <button onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ $dashboardUrl }}'" 
                            class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3.5 sm:px-7 sm:py-4 text-xs sm:text-sm font-semibold text-ganaderasoft-azul bg-white border border-gray-200 rounded-2xl shadow-md hover:bg-gray-50 hover:border-ganaderasoft-celeste transition-all duration-300 cursor-pointer">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-gray-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver atrás
                    </button>
                @else
                    <a href="{{ route('login') }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 sm:px-8 sm:py-4 text-xs sm:text-sm font-bold text-white bg-gradient-to-r from-ganaderasoft-celeste via-[#007B92] to-ganaderasoft-azul rounded-2xl shadow-xl shadow-ganaderasoft-azul/25 hover:shadow-2xl hover:scale-105 transition-all duration-300">
                        Iniciar sesión
                    </a>

                    <a href="{{ url('/') }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3.5 sm:px-7 sm:py-4 text-xs sm:text-sm font-semibold text-ganaderasoft-azul bg-white border border-gray-200 rounded-2xl shadow-md hover:bg-gray-50 hover:border-ganaderasoft-celeste transition-all duration-300">
                        Ir a la página principal
                    </a>
                @endif
            </div>

        </div>
    </div>
@endsection
