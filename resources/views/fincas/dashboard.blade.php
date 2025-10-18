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
            <a href="{{ route('rebanos.index') }}" class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition-shadow duration-200 border-t-4 border-ganaderasoft-celeste">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-ganaderasoft-celeste bg-opacity-10 p-3 rounded-lg">
                        <span class="text-4xl">🐄</span>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-2">Rebaños</h3>
                <p class="text-sm text-gray-600">Gestión de rebaños y grupos de animales</p>
                <div class="mt-4 flex items-center text-ganaderasoft-celeste font-medium text-sm">
                    <span>Ver rebaños</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Gestión de Animales - Animales -->
            <div class="bg-white rounded-xl shadow-md p-6 opacity-60 cursor-not-allowed border-t-4 border-gray-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gray-100 p-3 rounded-lg">
                        <span class="text-4xl">📋</span>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Animales</h3>
                <p class="text-sm text-gray-500">Registro individual de animales</p>
                <div class="mt-4">
                    <span class="inline-block bg-gray-200 text-gray-600 text-xs px-3 py-1 rounded-full">Próximamente</span>
                </div>
            </div>

            <!-- Gestión de Personal -->
            <a href="{{ route('personal.index') }}" class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition-shadow duration-200 border-t-4 border-ganaderasoft-verde">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-ganaderasoft-verde bg-opacity-10 p-3 rounded-lg">
                        <span class="text-4xl">👥</span>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-2">Personal de Finca</h3>
                <p class="text-sm text-gray-600">Gestión de trabajadores y personal</p>
                <div class="mt-4 flex items-center text-ganaderasoft-verde font-medium text-sm">
                    <span>Ver personal</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Módulo Reproductivo - Calendario -->
            <div class="bg-white rounded-xl shadow-md p-6 opacity-60 cursor-not-allowed border-t-4 border-gray-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gray-100 p-3 rounded-lg">
                        <span class="text-4xl">💝</span>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Calendario Reproductivo</h3>
                <p class="text-sm text-gray-500">Control de ciclos y eventos reproductivos</p>
                <div class="mt-4">
                    <span class="inline-block bg-gray-200 text-gray-600 text-xs px-3 py-1 rounded-full">Próximamente</span>
                </div>
            </div>

            <!-- Producción Lechera -->
            <div class="bg-white rounded-xl shadow-md p-6 opacity-60 cursor-not-allowed border-t-4 border-gray-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gray-100 p-3 rounded-lg">
                        <span class="text-4xl">🥛</span>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Registro Diario</h3>
                <p class="text-sm text-gray-500">Registro de producción lechera diaria</p>
                <div class="mt-4">
                    <span class="inline-block bg-gray-200 text-gray-600 text-xs px-3 py-1 rounded-full">Próximamente</span>
                </div>
            </div>

            <!-- Módulo Sanitario -->
            <div class="bg-white rounded-xl shadow-md p-6 opacity-60 cursor-not-allowed border-t-4 border-gray-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gray-100 p-3 rounded-lg">
                        <span class="text-4xl">🏥</span>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Plan de Vacunación</h3>
                <p class="text-sm text-gray-500">Control de vacunas y tratamientos</p>
                <div class="mt-4">
                    <span class="inline-block bg-gray-200 text-gray-600 text-xs px-3 py-1 rounded-full">Próximamente</span>
                </div>
            </div>

            <!-- Reportes -->
            <div class="bg-white rounded-xl shadow-md p-6 opacity-60 cursor-not-allowed border-t-4 border-gray-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gray-100 p-3 rounded-lg">
                        <span class="text-4xl">📊</span>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Reportes Productivos</h3>
                <p class="text-sm text-gray-500">Informes y análisis de producción</p>
                <div class="mt-4">
                    <span class="inline-block bg-gray-200 text-gray-600 text-xs px-3 py-1 rounded-full">Próximamente</span>
                </div>
            </div>
        </div>

        <!-- Información de la Finca -->
        <div class="mt-8 bg-white rounded-xl shadow-md p-6">
            <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-4">Información de la Finca</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Propietario</p>
                    <p class="font-medium text-gray-900">
                        {{ $finca['propietario']['Nombre'] ?? '' }} {{ $finca['propietario']['Apellido'] ?? '' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Teléfono</p>
                    <p class="font-medium text-gray-900">{{ $finca['propietario']['Telefono'] ?? 'N/A' }}</p>
                </div>
                @if(isset($finca['terreno']) && $finca['terreno'])
                    <div>
                        <p class="text-sm text-gray-500">Superficie</p>
                        <p class="font-medium text-gray-900">{{ $finca['terreno']['Superficie'] ?? 'N/A' }} ha</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Relieve</p>
                        <p class="font-medium text-gray-900">{{ $finca['terreno']['Relieve'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Textura del Suelo</p>
                        <p class="font-medium text-gray-900">{{ $finca['terreno']['Suelo_Textura'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">pH del Suelo</p>
                        <p class="font-medium text-gray-900">{{ $finca['terreno']['ph_Suelo'] ?? 'N/A' }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
