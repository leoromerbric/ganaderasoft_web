@extends('layouts.authenticated')

@section('title', 'Detalle de lactancia')

@section('content')
@php
    $lactanciaId = data_get($lactancia, 'id');
    $animalId = data_get($lactancia, 'animal_id') ?? data_get($lactancia, 'animal.id');
    $animalNombre = data_get($lactancia, 'animal.nombre')
        ?? ('Animal #'.($animalId ?? 'N/A'));
    $animalCodigo = data_get($lactancia, 'animal.codigo_animal') ?? '';
    $etapaNombre = data_get($lactancia, 'etapa.nombre')
        ?? data_get($lactancia, 'etapa_animal.etapa.nombre')
        ?? data_get($lactancia, 'animal.etapa_actual.etapa.nombre')
        ?? data_get($lactancia, 'animal.etapa_actual.nombre')
        ?? 'Etapa no disponible';

    $fechaInicio = data_get($lactancia, 'fecha_inicio');
    $fechaFin    = data_get($lactancia, 'fecha_fin');
    $secado      = data_get($lactancia, 'secado');

    $isActiva = empty($fechaFin) || strtotime($fechaFin) > time();
@endphp

<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-pink-50 text-pink-600 border border-pink-100 flex items-center justify-center font-bold text-2xl shadow-xs">
                🐄
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Período de lactancia #{{ $lactanciaId ?? 'N/A' }}
                </h1>
                <p class="text-gray-500 text-sm mt-1 flex items-center gap-2">
                    Ciclo productivo de <span class="font-bold text-gray-800">{{ $animalNombre }}</span>
                    @if($animalCodigo)
                        <span class="font-mono text-gray-500">(#{{ $animalCodigo }})</span>
                    @endif
                </p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            @if($lactanciaId)
                <a href="{{ route('lactancia.edit', $lactanciaId) }}"
                   class="px-6 py-3 bg-ganaderasoft-azul hover:bg-opacity-90 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar período
                </a>
            @endif
            <a href="{{ route('lactancia.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <!-- Card 1: Hembra Asociada -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>🐄</span> Hembra asociada
                </h3>

                <div class="p-4 bg-gray-50 border border-gray-100 rounded-2xl flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl bg-pink-50 border border-pink-100 text-pink-600 font-bold flex items-center justify-center text-2xl shadow-xs">
                            🐄
                        </div>
                        <div>
                            <p class="text-lg font-bold text-gray-900">{{ $animalNombre }}</p>
                            @if($animalCodigo)
                                <p class="text-xs text-gray-500 font-mono">Código: #{{ $animalCodigo }}</p>
                            @endif
                            <p class="text-xs text-gray-500 font-semibold mt-0.5">Etapa: {{ $etapaNombre }}</p>
                        </div>
                    </div>

                    @if($animalId)
                        <div>
                            <a href="{{ route('animales.show', $animalId) }}"
                               class="px-4 py-2 bg-white hover:bg-gray-100 border border-gray-200 text-gray-700 font-semibold rounded-xl text-xs flex items-center gap-1.5 transition-colors shadow-xs">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Ver ficha del animal
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card 2: Cronología del Ciclo Productivo -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>📅</span> Cronología del ciclo productivo
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-1">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha de inicio</span>
                        <p class="text-lg font-bold text-gray-900">
                            {{ $fechaInicio ? \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') : 'N/A' }}
                        </p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-1">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha de fin</span>
                        <p class="text-lg font-bold text-gray-900">
                            @if($fechaFin)
                                {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}
                            @else
                                <span class="text-emerald-700 font-semibold text-xs px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-100">En curso</span>
                            @endif
                        </p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-1">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha de secado</span>
                        <p class="text-lg font-bold text-gray-900">
                            @if($secado)
                                {{ \Carbon\Carbon::parse($secado)->format('d/m/Y') }}
                            @else
                                <span class="text-gray-400 font-medium text-sm">No registrada</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 3: Producción Lechera Vinculada -->
            <div class="p-6 bg-amber-50/50 border border-amber-200 rounded-2xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-white border border-amber-200 text-amber-700 font-bold flex items-center justify-center text-2xl shadow-xs">
                        🥛
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-gray-900">Producción de leche</h4>
                        <p class="text-xs text-gray-600 mt-0.5">Control y registros de pesaje de ordeño asociados a este período</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('leche.index', ['lactancia_id' => $lactanciaId]) }}"
                       class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-xl text-xs inline-flex items-center gap-2 transition-colors shadow-sm">
                        <span>🥛</span> Ver registros de leche
                    </a>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Panel de Estado y Metadatos (1 Tercio) -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-50 border-b border-gray-100 px-6 py-4">
                    <h3 class="text-base font-bold text-ganaderasoft-negro flex items-center gap-2">
                        <span>⚙️</span> Estado del sistema
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    <div class="space-y-3 text-xs text-gray-600 border-b border-gray-100 pb-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Estado del ciclo:</span>
                            @if($isActiva)
                                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    🟢 Activa
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-gray-100 text-gray-700 border border-gray-200">
                                    ⚪ Finalizada
                                </span>
                            @endif
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">ID lactancia:</span>
                            <span class="font-bold text-gray-900 font-mono">#{{ $lactanciaId }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Fecha de registro:</span>
                            <span class="font-semibold text-gray-800">{{ isset($lactancia['created_at']) ? date('d/m/Y H:i', strtotime($lactancia['created_at'])) : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Última actualización:</span>
                            <span class="font-semibold text-gray-800">{{ isset($lactancia['updated_at']) ? date('d/m/Y H:i', strtotime($lactancia['updated_at'])) : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
