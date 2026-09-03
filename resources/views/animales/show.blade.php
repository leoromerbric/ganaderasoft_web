@extends('layouts.authenticated')

@section('title', 'Detalle del Animal — ' . ($animal['nombre'] ?? 'Animal'))

@section('content')
<div class="space-y-8">
    <!-- Header Card -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl {{ ($animal['sexo'] ?? '') === 'M' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-pink-50 text-pink-600 border border-pink-100' }} flex items-center justify-center font-bold text-2xl shadow-xs">
                {{ ($animal['sexo'] ?? '') === 'M' ? '🐂' : '🐄' }}
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    {{ $animal['nombre'] ?? 'Sin Nombre' }}
                </h1>
                <p class="text-gray-500 text-sm mt-1 flex items-center gap-2">
                    Código: <span class="font-mono font-bold text-gray-800">#{{ $animal['codigo_animal'] ?? 'N/A' }}</span>
                </p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('arbol-gen.show', $animal['id']) }}"
               class="px-6 py-3 bg-ganaderasoft-celeste hover:bg-opacity-90 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2 text-sm">
                <span>🌳</span> Árbol genealógico
            </a>
            <a href="{{ route('animales.edit', $animal['id']) }}" 
               class="px-6 py-3 bg-ganaderasoft-azul text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar animal
            </a>
            @if(!empty($animal['archivado']))
                <form action="{{ route('animales.desarchivar', $animal['id']) }}" method="POST" class="inline" onsubmit="return confirm('¿Confirma que desea desarchivar y reactivar este animal en el sistema?');">
                    @csrf
                    <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2 text-sm cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Desarchivar animal
                    </button>
                </form>
            @endif
            <a href="{{ route('animales.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Ver listado
            </a>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Izquierda: Información Principal (2 Tercios) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Información General -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-6 flex items-center gap-2 border-b border-gray-100 pb-3">
                    <span>📋</span> Información general
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nombre</p>
                        <p class="text-lg font-bold text-gray-900">{{ $animal['nombre'] ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Código identificador</p>
                        <p class="text-lg font-bold text-gray-900 font-mono">#{{ $animal['codigo_animal'] ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Sexo</p>
                        <div class="mt-1">
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full border {{ ($animal['sexo'] ?? '') === 'M' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-pink-50 text-pink-700 border-pink-200' }}">
                                {{ ($animal['sexo'] ?? '') === 'M' ? '♂ Macho' : '♀ Hembra' }}
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha de nacimiento / edad</p>
                        <p class="text-lg font-bold text-gray-900">
                            {{ isset($animal['fecha_nacimiento']) ? date('d/m/Y', strtotime($animal['fecha_nacimiento'])) : 'N/A' }}
                        </p>
                        <p class="text-xs text-gray-500 font-medium mt-0.5">
                            @if(!empty($animal['edad_formateada']))
                                Edad: <span class="font-bold text-ganaderasoft-azul">{{ $animal['edad_formateada'] }}</span>
                                @if(isset($animal['edad_dias']))
                                    <span class="text-gray-400">({{ number_format($animal['edad_dias']) }} días)</span>
                                @endif
                            @elseif(isset($animal['fecha_nacimiento']))
                                Edad: <span class="font-bold text-ganaderasoft-azul">{{ \Carbon\Carbon::parse($animal['fecha_nacimiento'])->diffForHumans(null, true) }}</span>
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Procedencia</p>
                        <p class="text-lg font-bold text-gray-900">{{ $animal['procedencia'] ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Estado de registro</p>
                        <div class="mt-1">
                            @if(!empty($animal['archivado']))
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-700 border border-gray-200">Archivado</span>
                            @else
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Activo</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de Raza -->
            @if(isset($animal['composicion_raza']))
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-6 flex items-center gap-2 border-b border-gray-100 pb-3">
                    <span>🧬</span> Genética y raza
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nombre de raza</p>
                        <p class="text-base font-bold text-gray-900">{{ data_get($animal, 'composicion_raza.nombre', 'N/A') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Siglas</p>
                        <p class="text-base font-bold text-gray-900 font-mono">{{ data_get($animal, 'composicion_raza.siglas', 'N/A') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Propósito</p>
                        <p class="text-base font-bold text-gray-900">{{ data_get($animal, 'composicion_raza.proposito', 'N/A') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Origen</p>
                        <p class="text-base font-bold text-gray-900">{{ data_get($animal, 'composicion_raza.origen', 'N/A') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Ubicación (Rebaño y Finca) -->
            @if(isset($animal['rebano']))
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-6 flex items-center gap-2 border-b border-gray-100 pb-3">
                    <span>🏡</span> Ubicación y unidad de producción
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="w-10 h-10 rounded-lg bg-ganaderasoft-celeste/15 flex items-center justify-center text-ganaderasoft-azul font-bold text-lg">
                            🐄
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Rebaño perteneciente</p>
                            <p class="text-base font-bold text-gray-900">{{ data_get($animal, 'rebano.nombre', 'N/A') }}</p>
                        </div>
                    </div>
                    @if(isset($animal['rebano']['finca']))
                    <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="w-10 h-10 rounded-lg bg-ganaderasoft-verde/20 flex items-center justify-center text-ganaderasoft-verde-oscuro font-bold text-lg">
                            🏡
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Finca / predio</p>
                            <p class="text-base font-bold text-gray-900">{{ data_get($animal, 'rebano.finca.nombre', 'N/A') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Columna Derecha: Tarjetas de Estado y Sistema (1 Tercio) -->
        <div class="space-y-6">
            <!-- Etapa Actual Card (V2 Estándar) -->
            @php
                $nombreEtapa = data_get($animal, 'etapa_actual.etapa.nombre');
                $fechaIniEtapa = data_get($animal, 'etapa_actual.fecha_ini');
                $edadIni = data_get($animal, 'etapa_actual.etapa.edad_ini');
                $edadFin = data_get($animal, 'etapa_actual.etapa.edad_fin');
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-50 border-b border-gray-100 px-6 py-4">
                    <h3 class="text-base font-bold text-ganaderasoft-negro flex items-center gap-2">
                        <span>📊</span> Etapa de crecimiento
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Etapa alcanzada</label>
                        @if($nombreEtapa)
                            <span class="inline-flex px-3 py-1 text-sm font-bold rounded-full bg-blue-50 text-blue-800 border border-blue-200">
                                {{ $nombreEtapa }}
                            </span>
                        @else
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                Sin etapa asignada
                            </span>
                        @endif
                    </div>
                    @if($fechaIniEtapa)
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha de inicio en etapa</label>
                        <p class="text-base font-bold text-gray-900">
                            {{ date('d/m/Y', strtotime($fechaIniEtapa)) }}
                        </p>
                    </div>
                    @endif
                    @if($edadIni !== null && $edadFin !== null)
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Rango biológico estimado</label>
                        <p class="text-base font-bold text-gray-900">
                            {{ $edadIni }} - {{ $edadFin }} días
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Estado de Salud Actual Card (V2 Estándar) -->
            @php
                $nombreEstado = data_get($animal, 'estado_actual.estado_salud.nombre', 'Sano');
                $fechaIniEstado = data_get($animal, 'estado_actual.fecha_ini');
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-50 border-b border-gray-100 px-6 py-4">
                    <h3 class="text-base font-bold text-ganaderasoft-negro flex items-center gap-2">
                        <span>🩺</span> Estado de salud actual
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Diagnóstico / Condición</label>
                        <span class="inline-flex px-3 py-1 text-sm font-bold rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                            {{ $nombreEstado }}
                        </span>
                    </div>
                    @if($fechaIniEstado)
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha de diagnóstico</label>
                        <p class="text-base font-bold text-gray-900">
                            {{ date('d/m/Y', strtotime($fechaIniEstado)) }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Información del Sistema Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-50 border-b border-gray-100 px-6 py-4">
                    <h3 class="text-base font-bold text-ganaderasoft-negro flex items-center gap-2">
                        <span>⚙️</span> Registro del sistema
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha de creación</label>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ isset($animal['created_at']) ? date('d/m/Y H:i', strtotime($animal['created_at'])) : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Última actualización</label>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ isset($animal['updated_at']) ? date('d/m/Y H:i', strtotime($animal['updated_at'])) : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
