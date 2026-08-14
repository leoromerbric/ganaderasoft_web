@extends('layouts.finca')

@section('title', 'Gestión de Finca')

@section('content')
@php
    $fincaId = $finca['id'] ?? null;
    $nombreFinca = $finca['nombre'] ?? 'Finca';
    $tipoExp = $finca['explotacion_tipo'] ?? '';
    $terreno = $finca['terreno'] ?? [];
    
    // Propietario V2
    $propObj = $finca['propietario'] ?? null;
    $persona = $propObj['persona'] ?? null;
    $nombreProp = $persona ? trim(($persona['nombre'] ?? '').' '.($persona['apellido'] ?? '')) : '-';
    $telefonoProp = $persona['telefono'] ?? '-';
@endphp
<div class="space-y-8">
    <!-- Page Header & Banner -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-3xl">
                    🏡
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <h1 class="text-3xl font-bold text-ganaderasoft-negro">{{ $nombreFinca }}</h1>
                        <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                            Finca Activa
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-0.5">Gestión integral de la unidad de producción #{{ $fincaId }} (API V2)</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <span class="px-3.5 py-1.5 bg-ganaderasoft-celeste/15 text-ganaderasoft-azul border border-ganaderasoft-celeste/30 text-xs font-semibold rounded-full">
                {{ $tipoExp }}
            </span>
            <a href="{{ route('fincas.edit', $fincaId) }}" 
               class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar Datos
            </a>
            <a href="{{ route('fincas.index') }}" 
               class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors flex items-center">
                Ver Todas las Fincas
            </a>
        </div>
    </div>

    <!-- Modules Navigation Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Rebaños -->
        <a href="{{ route('rebanos.index', ['finca_id' => $fincaId]) }}" class="group bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition-all duration-200 border-t-4 border-ganaderasoft-azul flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 rounded-xl bg-ganaderasoft-azul/10 flex items-center justify-center text-3xl group-hover:scale-105 transition-transform">
                        🐄
                    </div>
                </div>
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-1">Rebaños</h3>
                <p class="text-xs text-gray-500 mb-4">Gestión de agrupaciones y lotes de ganado de esta finca</p>
            </div>
            <div class="flex items-center text-ganaderasoft-azul font-semibold text-sm group-hover:text-ganaderasoft-celeste transition-colors pt-2">
                <span>Ver rebaños</span>
                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        <!-- Lista de Animales -->
        <a href="{{ route('animales.index', ['finca_id' => $fincaId]) }}" class="group bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition-all duration-200 border-t-4 border-ganaderasoft-celeste flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-3xl group-hover:scale-105 transition-transform">
                        📋
                    </div>
                </div>
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-1">Inventario de Animales</h3>
                <p class="text-xs text-gray-500 mb-4">Registro y fichas individuales de los animales de la finca</p>
            </div>
            <div class="flex items-center text-ganaderasoft-celeste font-semibold text-sm group-hover:text-ganaderasoft-azul transition-colors pt-2">
                <span>Ver animales</span>
                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        <!-- Personal de Finca -->
        <a href="{{ route('personal-finca.index', ['finca_id' => $fincaId]) }}" class="group bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition-all duration-200 border-t-4 border-ganaderasoft-verde-oscuro flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 rounded-xl bg-ganaderasoft-verde/20 flex items-center justify-center text-3xl group-hover:scale-105 transition-transform">
                        👥
                    </div>
                </div>
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-1">Personal de Finca</h3>
                <p class="text-xs text-gray-500 mb-4">Trabajadores, técnicos y veterinarios asignados</p>
            </div>
            <div class="flex items-center text-ganaderasoft-verde-oscuro font-semibold text-sm group-hover:text-ganaderasoft-verde transition-colors pt-2">
                <span>Ver personal</span>
                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
    </div>

    <!-- Technical & Environmental Data Card -->
    <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100 space-y-6">
        <h3 class="text-lg font-bold text-ganaderasoft-negro flex items-center pb-3 border-b border-gray-100">
            <span class="w-8 h-8 rounded-lg bg-ganaderasoft-celeste/15 flex items-center justify-center mr-3 text-lg">🌾</span>
            Ficha Técnica e Información de Terreno
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-gray-50/70 p-4 rounded-xl border border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Propietario / Persona</p>
                <p class="font-bold text-ganaderasoft-negro">{{ $nombreProp }}</p>
                <p class="text-xs text-gray-500 mt-1">📞 {{ $telefonoProp }}</p>
            </div>

            <div class="bg-gray-50/70 p-4 rounded-xl border border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Superficie Total</p>
                <p class="font-bold text-ganaderasoft-negro">
                    {{ isset($terreno['superficie']) && (float)$terreno['superficie'] > 0 ? number_format((float)$terreno['superficie'], 1, ',', '.').' ha' : 'No registrada' }}
                </p>
            </div>

            <div class="bg-gray-50/70 p-4 rounded-xl border border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tipo de Relieve</p>
                <p class="font-bold text-ganaderasoft-negro">{{ $terreno['relieve'] ?? 'No especificado' }}</p>
            </div>

            <div class="bg-gray-50/70 p-4 rounded-xl border border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Textura del Suelo</p>
                <p class="font-bold text-ganaderasoft-negro">{{ $terreno['suelo_textura'] ?? 'No especificado' }}</p>
            </div>

            <div class="bg-gray-50/70 p-4 rounded-xl border border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">pH del Suelo</p>
                <p class="font-bold text-ganaderasoft-negro">{{ $terreno['ph_suelo'] ?? 'No especificado' }}</p>
            </div>

            <div class="bg-gray-50/70 p-4 rounded-xl border border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fuente de Agua & Riego</p>
                <p class="font-bold text-ganaderasoft-negro">
                    {{ $terreno['fuente_agua'] ?? 'No especificado' }}
                </p>
                <p class="text-xs text-gray-500 mt-1">Método: {{ $terreno['riego_metodo'] ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
