@extends('layouts.finca')

@section('title', 'Gestión de Finca')

@section('content')
    <div>
        <!-- Page Title -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">{{ $finca['Nombre'] ?? 'Gestión de Finca' }}</h2>
            <p class="text-gray-600 mt-1">{{ $finca['Explotacion_Tipo'] ?? '' }} - Gestión general de la finca</p>
        </div>

        <!-- Módulos Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Gestión de Animales - Rebaños -->
            <a href="{{ route('rebanos.index') }}" class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition-all duration-200 border-t-4 border-ganaderasoft-azul hover:border-ganaderasoft-celeste">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-ganaderasoft-azul bg-opacity-10 p-3 rounded-lg">
                        <span class="text-4xl">🐄</span>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-2">Rebaños</h3>
                <p class="text-sm text-gray-600 mb-4">Gestión de rebaños y grupos de animales</p>
                <div class="mt-4 flex items-center text-ganaderasoft-azul font-semibold text-sm hover:text-ganaderasoft-celeste">
                    <span>Ver rebaños</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Gestión de Animales - Animales -->
            <a href="{{ route('animales.index') }}" class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition-all duration-200 border-t-4 border-ganaderasoft-celeste hover:border-ganaderasoft-azul">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-ganaderasoft-celeste bg-opacity-10 p-3 rounded-lg">
                        <span class="text-4xl">📋</span>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-2">Animales</h3>
                <p class="text-sm text-gray-600 mb-4">Registro individual de animales</p>
                <div class="mt-4 flex items-center text-ganaderasoft-celeste font-semibold text-sm hover:text-ganaderasoft-azul">
                    <span>Ver animales</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Gestión de Personal -->
            <a href="{{ route('personal.index') }}" class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition-all duration-200 border-t-4 border-ganaderasoft-verde-oscuro hover:border-ganaderasoft-verde">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-ganaderasoft-verde-oscuro bg-opacity-10 p-3 rounded-lg">
                        <span class="text-4xl">👥</span>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-2">Personal de Finca</h3>
                <p class="text-sm text-gray-600 mb-4">Gestión de trabajadores y personal</p>
                <div class="mt-4 flex items-center text-ganaderasoft-verde-oscuro font-semibold text-sm hover:text-ganaderasoft-verde">
                    <span>Ver personal</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Módulo Reproductivo - Calendario -->
            <div class="bg-white rounded-xl shadow-md p-6 opacity-50 cursor-not-allowed border-t-4 border-gray-400">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gray-200 p-3 rounded-lg">
                        <span class="text-4xl">💝</span>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-gray-500 mb-2">Calendario Reproductivo</h3>
                <p class="text-sm text-gray-500 mb-4">Control de ciclos y eventos reproductivos</p>
                <div class="mt-4">
                    <span class="inline-block bg-gray-300 text-gray-600 text-xs font-medium px-3 py-1 rounded-full">Próximamente</span>
                </div>
            </div>

            <!-- Producción Lechera -->
            <div class="bg-white rounded-xl shadow-md p-6 opacity-50 cursor-not-allowed border-t-4 border-gray-400">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gray-200 p-3 rounded-lg">
                        <span class="text-4xl">🥛</span>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-gray-500 mb-2">Registro Diario</h3>
                <p class="text-sm text-gray-500 mb-4">Registro de producción lechera diaria</p>
                <div class="mt-4">
                    <span class="inline-block bg-gray-300 text-gray-600 text-xs font-medium px-3 py-1 rounded-full">Próximamente</span>
                </div>
            </div>

            <!-- Módulo Sanitario -->
            <div class="bg-white rounded-xl shadow-md p-6 opacity-50 cursor-not-allowed border-t-4 border-gray-400">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gray-200 p-3 rounded-lg">
                        <span class="text-4xl">🏥</span>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-gray-500 mb-2">Plan de Vacunación</h3>
                <p class="text-sm text-gray-500 mb-4">Control de vacunas y tratamientos</p>
                <div class="mt-4">
                    <span class="inline-block bg-gray-300 text-gray-600 text-xs font-medium px-3 py-1 rounded-full">Próximamente</span>
                </div>
            </div>

            <!-- Reportes -->
            <div class="bg-white rounded-xl shadow-md p-6 opacity-50 cursor-not-allowed border-t-4 border-gray-400">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gray-200 p-3 rounded-lg">
                        <span class="text-4xl">📊</span>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-gray-500 mb-2">Reportes Productivos</h3>
                <p class="text-sm text-gray-500 mb-4">Informes y análisis de producción</p>
                <div class="mt-4">
                    <span class="inline-block bg-gray-300 text-gray-600 text-xs font-medium px-3 py-1 rounded-full">Próximamente</span>
                </div>
            </div>
        </div>

        <!-- Información de la Finca -->
        <div class="mt-8 bg-white rounded-xl shadow-md p-6 border-l-4 border-ganaderasoft-celeste">
            <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-6 flex items-center">
                <span class="text-2xl mr-2">🏡</span>
                Información de la Finca
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm font-semibold text-gray-600 mb-2">Propietario</p>
                    <p class="font-semibold text-ganaderasoft-negro">
                        {{ $finca['propietario']['Nombre'] ?? '' }} {{ $finca['propietario']['Apellido'] ?? '' }}
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm font-semibold text-gray-600 mb-2">Teléfono</p>
                    <p class="font-semibold text-ganaderasoft-negro">{{ $finca['propietario']['Telefono'] ?? 'N/A' }}</p>
                </div>
                @if(isset($finca['terreno']) && $finca['terreno'])
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-semibold text-gray-600 mb-2">Superficie</p>
                        <p class="font-semibold text-ganaderasoft-negro">{{ $finca['terreno']['Superficie'] ?? 'N/A' }} ha</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-semibold text-gray-600 mb-2">Relieve</p>
                        <p class="font-semibold text-ganaderasoft-negro">{{ $finca['terreno']['Relieve'] ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-semibold text-gray-600 mb-2">Textura del Suelo</p>
                        <p class="font-semibold text-ganaderasoft-negro">{{ $finca['terreno']['Suelo_Textura'] ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-semibold text-gray-600 mb-2">pH del Suelo</p>
                        <p class="font-semibold text-ganaderasoft-negro">{{ $finca['terreno']['ph_Suelo'] ?? 'N/A' }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
