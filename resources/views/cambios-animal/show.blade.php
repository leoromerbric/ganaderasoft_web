@extends('layouts.authenticated')

@section('title', 'Detalle de cambio de animal')

@section('content')
@php
    $idCambio       = $cambio['id'] ?? 'N/A';
    $anId           = $cambio['animal_id'] ?? data_get($cambio, 'animal.id') ?? '';
    $animalNombre   = data_get($cambio, 'animal.nombre') ?? $cambio['animal_nombre'] ?? ($anId ? 'Animal #'.$anId : 'Animal sin especificar');
    $animalCodigo   = data_get($cambio, 'animal.codigo_animal') ?? $cambio['codigo_animal'] ?? '';
    $animalSexo     = data_get($cambio, 'animal.sexo') ?? '';
    $animalRaza     = data_get($cambio, 'animal.raza.nombre') ?? data_get($cambio, 'animal.raza') ?? 'No especificada';
    $animalRebano   = data_get($cambio, 'animal.rebano.nombre') ?? data_get($cambio, 'animal.rebano') ?? 'No especificado';
    $animalFinca    = data_get($cambio, 'animal.rebano.finca.nombre') ?? data_get($cambio, 'animal.finca.nombre') ?? 'No especificada';
    $etapa          = strtolower($cambio['etapa_cambio'] ?? '');
    $fechaCambio    = isset($cambio['fecha_cambio']) ? date('d/m/Y', strtotime($cambio['fecha_cambio'])) : '--/--/----';
    $peso           = !empty($cambio['peso']) ? (float)$cambio['peso'] : null;
    $altura         = !empty($cambio['altura']) ? (float)$cambio['altura'] : null;
    $comentario     = $cambio['comentario'] ?? '';
@endphp

<div class="space-y-8">
    <!-- Header Card -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl shadow-sm border border-ganaderasoft-celeste/20">
                📝
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Cambio #{{ $idCambio }}
                </h1>
                <p class="text-gray-500 text-sm mt-1 flex items-center gap-2">
                    Animal: <span class="font-medium text-gray-800">{{ $animalNombre }}</span>
                    @if($animalCodigo)
                        <span class="text-xs text-gray-400 font-mono">(#{{ $animalCodigo }})</span>
                    @endif
                    • Etapa: <span class="font-bold text-ganaderasoft-azul">{{ $cambio['etapa_cambio'] ?? 'N/A' }}</span>
                </p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('cambios-animal.create') }}" 
               class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2 text-sm">
                <span class="text-base font-bold">+</span> Nuevo cambio
            </a>
            <a href="{{ route('cambios-animal.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Ver listado
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
        <!-- Columna Izquierda: Información Principal (2 Tercios) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información del Cambio -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-6 flex items-center gap-2">
                    <span>📋</span> Información del cambio de etapa
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha del cambio</p>
                        <p class="text-lg font-bold text-gray-900">
                            {{ $fechaCambio }}
                            @if(isset($cambio['fecha_cambio']))
                                <span class="text-xs text-gray-500 font-normal">({{ \Carbon\Carbon::parse($cambio['fecha_cambio'])->diffForHumans() }})</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Etapa alcanzada</p>
                        <div class="mt-1">
                            <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full border
                                {{ in_array($etapa, ['becerro','becerra']) ? 'bg-amber-50 text-amber-700 border-amber-200' : ($etapa === 'juvenil' ? 'bg-blue-50 text-blue-700 border-blue-200' : (in_array($etapa, ['adulto','adulta']) ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-gray-100 text-gray-700 border-gray-200')) }}">
                                {{ $cambio['etapa_cambio'] ?? 'Sin etapa' }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">ID registro etapa</p>
                        <p class="text-lg font-bold text-gray-900 font-mono">#{{ $cambio['animal_etapa_id'] ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Estado del registro</p>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Activo
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medidas Físicas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-6 flex items-center gap-2">
                    <span>📏</span> Medidas físicas registradas
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-6 bg-blue-50/60 border border-blue-100 rounded-2xl flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Peso corporal</p>
                            <p class="text-3xl font-extrabold text-blue-700">
                                @if($peso)
                                    {{ number_format($peso, 1) }} <span class="text-base font-bold text-gray-500">kg</span>
                                @else
                                    <span class="text-gray-400 text-base font-normal">No registrado</span>
                                @endif
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl font-bold">
                            ⚖️
                        </div>
                    </div>

                    <div class="p-6 bg-emerald-50/60 border border-emerald-100 rounded-2xl flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Altura</p>
                            <p class="text-3xl font-extrabold text-emerald-700">
                                @if($altura)
                                    {{ number_format($altura, 1) }} <span class="text-base font-bold text-gray-500">cm</span>
                                @else
                                    <span class="text-gray-400 text-base font-normal">No registrado</span>
                                @endif
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl font-bold">
                            📐
                        </div>
                    </div>
                </div>
            </div>

            <!-- Observaciones y Comentarios -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-4 flex items-center gap-2">
                    <span>💬</span> Comentarios y observaciones
                </h3>
                @if(!empty($comentario))
                    <div class="p-4 bg-gray-50 border-l-4 border-ganaderasoft-celeste rounded-xl text-gray-700 text-sm leading-relaxed">
                        {{ $comentario }}
                    </div>
                @else
                    <p class="text-gray-400 text-sm font-medium py-2">Sin observaciones adicionales en este registro.</p>
                @endif
            </div>
        </div>

        <!-- Columna Derecha: Sidebar con Ficha del Animal e Info (1 Tercio) -->
        <div class="space-y-6">
            <!-- Ficha Resumen del Animal -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50/80 border-b border-gray-100 px-6 py-4">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <span>🐄</span> Expediente del animal
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl {{ $animalSexo === 'M' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600' }} flex items-center justify-center font-bold text-xl border border-gray-100">
                            {{ $animalSexo === 'M' ? '🐂' : '🐄' }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="font-bold text-gray-900 text-base truncate">{{ $animalNombre }}</p>
                            <p class="text-xs text-gray-400 font-mono">
                                {{ $animalCodigo ? 'Código: #'.$animalCodigo : ($anId ? 'ID: #'.$anId : 'Sin ID') }}
                            </p>
                        </div>
                    </div>

                    <div class="text-xs space-y-2 border-t border-gray-100 pt-3 text-gray-600">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-400">Raza:</span>
                            <span class="font-semibold text-gray-800">{{ $animalRaza }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-400">Rebaño:</span>
                            <span class="font-semibold text-gray-800">{{ $animalRebano }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-400">Finca:</span>
                            <span class="font-semibold text-gray-800">{{ $animalFinca }}</span>
                        </div>
                    </div>

                    @if($anId)
                        <div class="pt-2">
                            <a href="{{ route('animales.show', $anId) }}"
                               class="w-full py-2.5 bg-ganaderasoft-celeste/10 text-ganaderasoft-celeste hover:bg-ganaderasoft-celeste hover:text-white rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Ver expediente completo
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-3">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Información del registro</h3>
                <div class="text-xs space-y-2 text-gray-600">
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="text-gray-400">ID del cambio:</span>
                        <span class="font-mono font-bold text-gray-800">#{{ $idCambio }}</span>
                    </div>
                    @if(isset($cambio['created_at']))
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="text-gray-400">Fecha de registro:</span>
                        <span class="font-medium text-gray-800">{{ date('d/m/Y H:i', strtotime($cambio['created_at'])) }}</span>
                    </div>
                    @endif
                    @if(isset($cambio['updated_at']))
                    <div class="flex justify-between py-1">
                        <span class="text-gray-400">Última actualización:</span>
                        <span class="font-medium text-gray-800">{{ date('d/m/Y H:i', strtotime($cambio['updated_at'])) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection