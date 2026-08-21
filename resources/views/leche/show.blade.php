@extends('layouts.authenticated')

@section('title', 'Detalle de registro de leche')

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

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl">
                🥛
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Detalle de pesaje #{{ $lecheId ?? 'N/A' }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">Consulta los detalles del registro de producción lechera</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('leche.edit', $lecheId) }}"
               class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Editar
            </a>
            <a href="{{ route('leche.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Izquierda: Información Principal (2 Tercios) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Milk Volume Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>🥛</span> Volumen de leche producido
                </h3>

                <div class="p-6 bg-emerald-50/60 border border-emerald-100 rounded-2xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold text-emerald-900 uppercase tracking-wider mb-1">Cantidad registrada</p>
                        <p class="text-4xl font-black text-emerald-700">
                            {{ number_format($pesajeTotal, 2) }} <span class="text-2xl font-bold">Litros</span>
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Fecha de pesaje</p>
                        <p class="text-lg font-bold text-gray-900">
                            📅 {{ $fechaPesaje ? \Carbon\Carbon::parse($fechaPesaje)->format('d/m/Y') : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Animal Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>🐄</span> Hembra y lactancia asociada
                </h3>

                <div class="p-5 bg-pink-50/60 border border-pink-100 rounded-2xl flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl bg-white border border-pink-200 text-pink-700 font-bold flex items-center justify-center text-2xl shadow-xs">
                            🐄
                        </div>
                        <div>
                            <p class="text-lg font-bold text-gray-900">{{ $animalNombre }}</p>
                            @if($animalCodigo)
                                <p class="text-xs text-gray-500 font-mono mt-0.5">Código: #{{ $animalCodigo }}</p>
                            @endif
                            <p class="text-xs text-gray-600 font-semibold mt-1">ID lactancia: #{{ $lactanciaId }}</p>
                        </div>
                    </div>

                    @if($lactanciaId)
                        <div>
                            <a href="{{ route('lactancia.show', $lactanciaId) }}" 
                               class="px-4 py-2 bg-pink-100 hover:bg-pink-200 text-pink-800 font-bold rounded-xl text-xs transition-colors">
                                Ver lactancia
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Panel de Metadatos (1 Tercio) -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <span>⚙️</span> Información del sistema
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    <div class="space-y-3 text-xs text-gray-600">
                        <div class="flex justify-between">
                            <span>ID registro leche:</span>
                            <span class="font-bold text-gray-900 font-mono">#{{ $lecheId }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>ID lactancia:</span>
                            <span class="font-bold text-gray-900 font-mono">#{{ $lactanciaId }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Creado el:</span>
                            <span class="font-semibold text-gray-800">{{ isset($registroLeche['created_at']) ? date('d/m/Y H:i', strtotime($registroLeche['created_at'])) : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Actualizado el:</span>
                            <span class="font-semibold text-gray-800">{{ isset($registroLeche['updated_at']) ? date('d/m/Y H:i', strtotime($registroLeche['updated_at'])) : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
