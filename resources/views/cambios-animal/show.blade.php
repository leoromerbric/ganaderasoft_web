@extends('layouts.authenticated')

@section('title', 'Detalle del Cambio #' . ($cambio['id'] ?? ''))

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl">
                📝
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Cambio #{{ $cambio['id'] ?? 'N/A' }}
                </h1>
                <p class="text-gray-500 text-sm mt-1 flex items-center gap-2">
                    Etapa: <span class="font-bold text-gray-800">{{ $cambio['etapa_cambio'] ?? 'N/A' }}</span>
                    — Animal: <span class="font-bold text-gray-800">{{ $cambio['animal_nombre'] ?? data_get($cambio, 'animal.nombre') ?? ('Animal #'.($cambio['animal_id'] ?? 'N/A')) }}</span>
                </p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('cambios-animal.create') }}" 
               class="px-6 py-3 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2 text-sm">
                <span class="text-base font-bold">+</span> Nuevo cambio
            </a>
            <a href="{{ route('cambios-animal.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-lg">✅</span>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Izquierda: Información Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información del Cambio -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-6 flex items-center gap-2">
                    <span>📊</span> Registro del cambio de etapa
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha del cambio</p>
                        <p class="text-lg font-bold text-gray-900">
                            {{ date('d/m/Y', strtotime($cambio['fecha_cambio'])) }}
                            <span class="text-xs text-gray-500 font-normal">({{ \Carbon\Carbon::parse($cambio['fecha_cambio'])->diffForHumans() }})</span>
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Etapa alcanzada</p>
                        <div class="mt-1">
                            @php
                                $etapa = strtolower($cambio['etapa_cambio'] ?? '');
                            @endphp
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full border
                                {{ in_array($etapa, ['becerro','becerra']) ? 'bg-amber-50 text-amber-700 border-amber-200' : ($etapa === 'juvenil' ? 'bg-blue-50 text-blue-700 border-blue-200' : (in_array($etapa, ['adulto','adulta']) ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-gray-100 text-gray-700 border-gray-200')) }}">
                                {{ $cambio['etapa_cambio'] ?? 'Sin etapa' }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Animal evaluado</p>
                        <div class="mt-1">
                            <a href="{{ route('animales.show', data_get($cambio, 'animal.id', $cambio['animal_id'] ?? 0)) }}"
                               class="inline-flex items-center gap-2 px-3 py-1 bg-ganaderasoft-celeste/15 text-ganaderasoft-azul hover:bg-ganaderasoft-celeste hover:text-white rounded-full text-sm font-bold transition-all">
                                <span>🐄</span> {{ data_get($cambio, 'animal.nombre') ?? $cambio['animal_nombre'] ?? ('Animal #'.($cambio['animal_id'] ?? 'N/A')) }}
                            </a>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">ID registro etapa</p>
                        <p class="text-lg font-bold text-gray-900 font-mono">#{{ $cambio['animal_etapa_id'] ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Medidas Físicas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-6 flex items-center gap-2">
                    <span>📏</span> Medidas físicas registradas
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="text-center p-6 bg-blue-50/70 border border-blue-100 rounded-2xl">
                        <div class="text-3xl mb-2">⚖️</div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Peso corporal</h4>
                        <p class="text-3xl font-extrabold text-blue-700">
                            @if(!empty($cambio['peso']))
                                {{ number_format($cambio['peso'], 1) }} <span class="text-lg font-bold">Kg</span>
                            @else
                                <span class="text-gray-400 text-base font-normal">No registrado</span>
                            @endif
                        </p>
                    </div>

                    <div class="text-center p-6 bg-emerald-50/70 border border-emerald-100 rounded-2xl">
                        <div class="text-3xl mb-2">📐</div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Altura</h4>
                        <p class="text-3xl font-extrabold text-emerald-700">
                            @if(!empty($cambio['altura']))
                                {{ number_format($cambio['altura'], 1) }} <span class="text-lg font-bold">Cm</span>
                            @else
                                <span class="text-gray-400 text-base font-normal">No registrado</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Observaciones y Comentarios -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-4 flex items-center gap-2">
                    <span>💬</span> Comentarios y observaciones
                </h3>
                @if(!empty($cambio['comentario']))
                    <div class="p-4 bg-gray-50 border-l-4 border-ganaderasoft-celeste rounded-xl text-gray-700 text-sm leading-relaxed">
                        {{ $cambio['comentario'] }}
                    </div>
                @else
                    <p class="text-gray-400 text-sm font-medium py-2">Sin observaciones adicionales en este registro.</p>
                @endif
            </div>
        </div>

        <!-- Columna Derecha: Acciones y Sistema -->
        <div class="space-y-6">
            <!-- Acciones Rápidas Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-ganaderasoft-celeste text-white px-6 py-4">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <span>⚡</span> Acciones rápidas
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('cambios-animal.create') }}" 
                       class="w-full bg-ganaderasoft-verde-oscuro text-white px-4 py-2.5 rounded-xl hover:bg-opacity-90 transition-all font-semibold text-sm flex items-center justify-center gap-2 shadow-xs">
                        <span>➕</span> Registrar nuevo cambio
                    </a>
                    
                    <a href="{{ route('cambios-animal.index') }}" 
                       class="w-full border border-gray-300 text-gray-700 px-4 py-2.5 rounded-xl hover:bg-gray-50 transition-colors font-semibold text-sm flex items-center justify-center gap-2">
                        <span>📋</span> Ver todos los cambios
                    </a>
                    
                    @if(!empty($cambio['animal_id']))
                    <a href="{{ route('cambios-animal.index', ['animal_id' => $cambio['animal_id']]) }}" 
                       class="w-full bg-blue-50 text-blue-700 border border-blue-200 px-4 py-2.5 rounded-xl hover:bg-blue-100 transition-colors font-semibold text-sm flex items-center justify-center gap-2">
                        <span>🐄</span> Cambios de este animal
                    </a>
                    @endif
                </div>
            </div>

            <!-- Registro del Sistema Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <span>⚙️</span> Registro del sistema
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha de registro</label>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ isset($cambio['created_at']) ? date('d/m/Y H:i', strtotime($cambio['created_at'])) : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Última modificación</label>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ isset($cambio['updated_at']) ? date('d/m/Y H:i', strtotime($cambio['updated_at'])) : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tiempo transcurrido</label>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ \Carbon\Carbon::parse($cambio['fecha_cambio'])->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection