@extends('layouts.authenticated')

@section('title', 'Detalle de pesaje de leche')

@section('content')
@php
    $lecheId = data_get($registroLeche, 'id');
    $lactanciaId = data_get($registroLeche, 'lactancia_id');
    $fechaPesaje = data_get($registroLeche, 'fecha_pesaje');
    $pesajeTotal = (float) data_get($registroLeche, 'pesaje_total', 0);

    $animalNombre = data_get($registroLeche, 'animal.nombre')
        ?? data_get($registroLeche, 'lactancia.animal.nombre')
        ?? ('Animal #'.(data_get($registroLeche, 'lactancia.animal_id') ?? 'N/A'));

    $animalCodigo = data_get($registroLeche, 'animal.codigo_animal')
        ?? data_get($registroLeche, 'lactancia.animal.codigo_animal') ?? '';
@endphp

<div class="space-y-8">
    <!-- Header section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">Pesaje #{{ $lecheId ?? 'N/A' }}</h1>
            <p class="text-gray-500 text-sm mt-1">Detalle del pesaje de producción lechera individual</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            @if($lecheId)
                <a href="{{ route('leche.edit', $lecheId) }}"
                   class="px-6 py-3 bg-ganaderasoft-azul text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2 text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar pesaje
                </a>
            @endif
            <a href="{{ route('leche.index') }}" 
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
    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Columna Izquierda: Información Principal (2 Tercios) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Milk Volume Hero Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <div class="flex items-center space-x-3 border-b border-gray-100 pb-4">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 font-bold flex items-center justify-center text-base border border-emerald-100">
                        🥛
                    </div>
                    <h3 class="text-lg font-bold text-ganaderasoft-negro">Volumen registrado</h3>
                </div>

                <div class="p-6 bg-emerald-50/60 border border-emerald-100 rounded-2xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div>
                        <p class="text-xs font-semibold text-emerald-800 uppercase tracking-wider mb-1">Cantidad producida</p>
                        <p class="text-4xl font-extrabold text-emerald-700">
                            {{ number_format($pesajeTotal, 2, ',', '.') }} <span class="text-xl font-bold">Litros</span>
                        </p>
                    </div>
                    <div class="sm:text-right">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha del pesaje</p>
                        <p class="text-lg font-bold text-gray-900 flex items-center sm:justify-end gap-1.5">
                            <span>📅</span> {{ $fechaPesaje ? \Carbon\Carbon::parse($fechaPesaje)->format('d/m/Y') : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Animal and Lactation Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <div class="flex items-center space-x-3 border-b border-gray-100 pb-4">
                    <div class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 font-bold flex items-center justify-center text-base border border-pink-100">
                        🐄
                    </div>
                    <h3 class="text-lg font-bold text-ganaderasoft-negro">Hembra y ciclo de lactancia</h3>
                </div>

                <div class="p-5 bg-pink-50/50 border border-pink-100 rounded-2xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl bg-white border border-pink-200 text-pink-600 font-bold flex items-center justify-center text-2xl shadow-xs">
                            🐄
                        </div>
                        <div>
                            <p class="text-lg font-bold text-gray-900">{{ $animalNombre }}</p>
                            @if($animalCodigo)
                                <p class="text-xs text-gray-500 font-mono mt-0.5">Código: #{{ $animalCodigo }}</p>
                            @endif
                            <p class="text-xs text-pink-700 font-semibold mt-1">Período de lactancia #{{ $lactanciaId }}</p>
                        </div>
                    </div>

                    @if($lactanciaId)
                        <div>
                            <a href="{{ route('lactancia.show', $lactanciaId) }}" 
                               class="px-4 py-2 bg-pink-100 hover:bg-pink-200 text-pink-800 font-semibold rounded-xl text-xs transition-colors inline-flex items-center gap-1.5">
                                <span>🔍</span> Ver ciclo completo
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Panel de Metadatos y Acciones (1 Tercio) -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                <div class="bg-gray-50/80 border-b border-gray-100 px-6 py-4">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <span>⚙️</span> Información del registro
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    <div class="space-y-3 text-xs text-gray-600">
                        <div class="flex justify-between">
                            <span class="text-gray-500">ID de pesaje:</span>
                            <span class="font-bold text-gray-900 font-mono">#{{ $lecheId }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">ID de lactancia:</span>
                            <span class="font-bold text-gray-900 font-mono">#{{ $lactanciaId }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Fecha de registro:</span>
                            <span class="font-semibold text-gray-800">{{ isset($registroLeche['created_at']) ? date('d/m/Y H:i', strtotime($registroLeche['created_at'])) : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Última actualización:</span>
                            <span class="font-semibold text-gray-800">{{ isset($registroLeche['updated_at']) ? date('d/m/Y H:i', strtotime($registroLeche['updated_at'])) : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-ui.confirm-modal />
@endsection
