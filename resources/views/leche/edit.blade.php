@extends('layouts.authenticated')

@section('title', 'Editar pesaje de leche')

@section('content')
@php
    $lecheId = $registroLeche['id'] ?? null;
    $lactanciaIdReg = old('lactancia_id', $registroLeche['lactancia_id'] ?? null);
    $vFechaPesaje = old('fecha_pesaje') ?: (data_get($registroLeche, 'fecha_pesaje') ? substr(data_get($registroLeche, 'fecha_pesaje'), 0, 10) : date('Y-m-d'));
    $vPesajeTotal = old('pesaje_total', $registroLeche['pesaje_total'] ?? '');

    $animalNombre = data_get($registroLeche, 'animal.nombre')
        ?? data_get($registroLeche, 'lactancia.animal.nombre')
        ?? 'Animal no disponible';
    $animalCodigo = data_get($registroLeche, 'animal.codigo_animal')
        ?? data_get($registroLeche, 'lactancia.animal.codigo_animal') ?? '';
@endphp

<div class="space-y-8">
    <!-- Header section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">Editar pesaje #{{ $lecheId ?? 'N/A' }}</h1>
            <p class="text-gray-500 text-sm mt-1">Modifica la fecha o la cantidad producida en este pesaje</p>
        </div>
        <div>
            <a href="{{ route('leche.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Error Messages -->
    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm">
            <p class="text-sm font-bold mb-1">Por favor corrige los siguientes errores:</p>
            <ul class="list-disc list-inside text-sm pl-2 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Container -->
    <form action="{{ route('leche.update', $lecheId) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Período de Lactancia -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <div class="flex items-center space-x-3 border-b border-gray-100 pb-4">
                        <div class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 font-bold flex items-center justify-center text-base border border-pink-100">
                            🐄
                        </div>
                        <h3 class="text-lg font-bold text-ganaderasoft-negro">Período de lactancia</h3>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                            Lactancia asignada <span class="text-red-500">*</span>
                        </label>
                        <select name="lactancia_id" id="lactancia_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('lactancia_id') border-red-500 @enderror">
                            @foreach($lactancias as $lactancia)
                                @php
                                    $lactId = $lactancia['id'] ?? null;
                                    $fechaInicio = isset($lactancia['fecha_inicio']) ? \Carbon\Carbon::parse($lactancia['fecha_inicio'])->format('d/m/Y') : '?';
                                    $fechaFin = !empty($lactancia['fecha_fin']) ? \Carbon\Carbon::parse($lactancia['fecha_fin'])->format('d/m/Y') : 'En curso';
                                    $anNombre = data_get($lactancia, 'animal.nombre') ?? ('Animal #'.(data_get($lactancia, 'animal_id') ?? 'N/A'));
                                    $anCod = data_get($lactancia, 'animal.codigo_animal') ?? '';
                                    $isSelected = (string)$lactanciaIdReg === (string)$lactId;
                                @endphp
                                @if($lactId)
                                    <option value="{{ $lactId }}" {{ $isSelected ? 'selected' : '' }}>
                                        {{ $anNombre }} ({{ $anCod ? '#'.$anCod : 'ID #'.$lactId }}) — {{ $fechaInicio }} al {{ $fechaFin }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('lactancia_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Card 2: Campos Modificables -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <div class="flex items-center space-x-3 border-b border-gray-100 pb-4">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 font-bold flex items-center justify-center text-base border border-emerald-100">
                            🥛
                        </div>
                        <h3 class="text-lg font-bold text-ganaderasoft-negro">Modificar datos de producción</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de pesaje <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="fecha_pesaje" id="fecha_pesaje" required value="{{ $vFechaPesaje }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('fecha_pesaje') border-red-500 @enderror">
                            @error('fecha_pesaje')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Cantidad producida (litros) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="pesaje_total" id="pesaje_total" required value="{{ $vPesajeTotal }}"
                                       step="0.01" min="0.01" placeholder="Ej: 14.50"
                                       class="w-full px-4 py-3 pr-16 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('pesaje_total') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                                    Litros
                                </div>
                            </div>
                            @error('pesaje_total')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Panel de Guardado (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    <div class="bg-gray-50/80 border-b border-gray-100 px-6 py-4">
                        <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <span>⚙️</span> Actualizar registro
                        </h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="text-xs text-gray-500 space-y-2 border-b border-gray-100 pb-4">
                            <div class="flex justify-between">
                                <span>ID registro:</span>
                                <span class="font-bold text-gray-900 font-mono">#{{ $lecheId }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Animal:</span>
                                <span class="font-bold text-gray-900">{{ $animalNombre }}</span>
                            </div>
                            @if($animalCodigo)
                                <div class="flex justify-between">
                                    <span>Código animal:</span>
                                    <span class="font-mono text-gray-700">#{{ $animalCodigo }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span>Creado el:</span>
                                <span class="font-semibold text-gray-800">{{ isset($registroLeche['created_at']) ? date('d/m/Y H:i', strtotime($registroLeche['created_at'])) : 'N/A' }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                                💾 Actualizar pesaje
                            </button>

                            <a href="{{ route('leche.index') }}"
                               class="w-full py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
