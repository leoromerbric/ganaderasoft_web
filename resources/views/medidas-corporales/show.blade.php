@extends('layouts.authenticated')

@section('title', 'Detalle de Medidas Corporales')

@section('content')
@php
    $medidaId = data_get($medidaCorporal, 'id');
    $animalId = data_get($medidaCorporal, 'animal_id') ?? data_get($medidaCorporal, 'etapa_animal.animal_id');
    
    $animalNombre = data_get($medidaCorporal, 'animal.nombre')
        ?? ('Animal #'.($animalId ?? 'N/A'));

    $animalCodigo = data_get($medidaCorporal, 'animal.codigo_animal') ?? '';

    $alturaHc    = (float) data_get($medidaCorporal, 'altura_hc', 0);
    $alturaHg    = (float) data_get($medidaCorporal, 'altura_hg', 0);
    $perimetroPt = (float) data_get($medidaCorporal, 'perimetro_pt', 0);
    $perimetroPca= (float) data_get($medidaCorporal, 'perimetro_pca', 0);
    $longitudLc  = (float) data_get($medidaCorporal, 'longitud_lc', 0);
    $longitudLg  = (float) data_get($medidaCorporal, 'longitud_lg', 0);
    $anchuraAg   = (float) data_get($medidaCorporal, 'anchura_ag', 0);
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl">
                📏
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Detalle de Medidas Corporales #{{ $medidaId ?? 'N/A' }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">Consulta las dimensiones morfométricas registradas para este animal</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('medidas-corporales.edit', $medidaId) }}"
               class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Editar
            </a>
            <a href="{{ route('medidas-corporales.index') }}" 
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
        <!-- Columna Izquierda: Detalles de Mediciones (2 Tercios) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Alturas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>📏</span> Alturas
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-emerald-50/60 border border-emerald-100 rounded-2xl">
                        <p class="text-xs font-bold text-emerald-900 uppercase tracking-wider mb-1">Altura a la Cruz (HC)</p>
                        <p class="text-2xl font-black text-emerald-700">
                            {{ $alturaHc > 0 ? number_format($alturaHc, 1).' cm' : 'No registrada' }}
                        </p>
                    </div>

                    <div class="p-4 bg-emerald-50/60 border border-emerald-100 rounded-2xl">
                        <p class="text-xs font-bold text-emerald-900 uppercase tracking-wider mb-1">Altura a la Grupa (HG)</p>
                        <p class="text-2xl font-black text-emerald-700">
                            {{ $alturaHg > 0 ? number_format($alturaHg, 1).' cm' : 'No registrada' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Perímetros -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>⭕</span> Perímetros
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-purple-50/60 border border-purple-100 rounded-2xl">
                        <p class="text-xs font-bold text-purple-900 uppercase tracking-wider mb-1">Perímetro Torácico (PT)</p>
                        <p class="text-2xl font-black text-purple-700">
                            {{ $perimetroPt > 0 ? number_format($perimetroPt, 1).' cm' : 'No registrado' }}
                        </p>
                    </div>

                    <div class="p-4 bg-purple-50/60 border border-purple-100 rounded-2xl">
                        <p class="text-xs font-bold text-purple-900 uppercase tracking-wider mb-1">Perímetro de Caña (PCA)</p>
                        <p class="text-2xl font-black text-purple-700">
                            {{ $perimetroPca > 0 ? number_format($perimetroPca, 1).' cm' : 'No registrado' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Longitudes y Anchura -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>📐</span> Longitudes y Anchura
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 bg-cyan-50/60 border border-cyan-100 rounded-2xl">
                        <p class="text-xs font-bold text-cyan-900 uppercase tracking-wider mb-1">Longitud Corporal (LC)</p>
                        <p class="text-xl font-black text-cyan-700">
                            {{ $longitudLc > 0 ? number_format($longitudLc, 1).' cm' : 'N/R' }}
                        </p>
                    </div>

                    <div class="p-4 bg-cyan-50/60 border border-cyan-100 rounded-2xl">
                        <p class="text-xs font-bold text-cyan-900 uppercase tracking-wider mb-1">Longitud de Grupa (LG)</p>
                        <p class="text-xl font-black text-cyan-700">
                            {{ $longitudLg > 0 ? number_format($longitudLg, 1).' cm' : 'N/R' }}
                        </p>
                    </div>

                    <div class="p-4 bg-cyan-50/60 border border-cyan-100 rounded-2xl">
                        <p class="text-xs font-bold text-cyan-900 uppercase tracking-wider mb-1">Anchura de Grupa (AG)</p>
                        <p class="text-xl font-black text-cyan-700">
                            {{ $anchuraAg > 0 ? number_format($anchuraAg, 1).' cm' : 'N/R' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Animal Evaluado -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>🐄</span> Animal Evaluado
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
                        </div>
                    </div>

                    @if($animalId)
                        <div>
                            <a href="{{ route('animales.show', $animalId) }}" 
                               class="px-4 py-2 bg-pink-100 hover:bg-pink-200 text-pink-800 font-bold rounded-xl text-xs transition-colors">
                                Ver Animal
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
                        <span>⚙️</span> Información del Sistema
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    <div class="space-y-3 text-xs text-gray-600">
                        <div class="flex justify-between">
                            <span>ID Registro:</span>
                            <span class="font-bold text-gray-900 font-mono">#{{ $medidaId }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Creado el:</span>
                            <span class="font-semibold text-gray-800">{{ isset($medidaCorporal['created_at']) ? date('d/m/Y H:i', strtotime($medidaCorporal['created_at'])) : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Actualizado el:</span>
                            <span class="font-semibold text-gray-800">{{ isset($medidaCorporal['updated_at']) ? date('d/m/Y H:i', strtotime($medidaCorporal['updated_at'])) : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
