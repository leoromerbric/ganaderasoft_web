@extends('layouts.authenticated')

@section('title', 'Editar Registro de Leche')

@section('content')
@php
    $lecheId = $registroLeche['id'] ?? null;
    $lactanciaIdReg = old('lactancia_id', $registroLeche['lactancia_id'] ?? null);
    $vFechaPesaje = old('fecha_pesaje') ?: (data_get($registroLeche, 'fecha_pesaje') ? substr(data_get($registroLeche, 'fecha_pesaje'), 0, 10) : date('Y-m-d'));
    $vPesajeTotal = old('pesaje_total', $registroLeche['pesaje_total'] ?? '');

    $animalNombre = data_get($registroLeche, 'animal.nombre')
        ?? data_get($registroLeche, 'lactancia.animal.nombre')
        ?? 'Animal no disponible';
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl">
                ✏️
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Editar Pesaje de Leche #{{ $lecheId ?? 'N/A' }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">Modifica la fecha o la cantidad producida en este pesaje</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('leche.show', $lecheId) }}"
               class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Ver Detalle
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Período de Lactancia -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🐄</span> Período de Lactancia
                    </h3>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                            Lactancia <span class="text-red-500">*</span>
                        </label>
                        <select name="lactancia_id" id="lactancia_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('lactancia_id') border-red-500 @enderror">
                            @foreach($lactancias as $lactancia)
                                @php
                                    $lactId = $lactancia['id'] ?? null;
                                    $fechaInicio = isset($lactancia['fecha_inicio']) ? \Carbon\Carbon::parse($lactancia['fecha_inicio'])->format('d/m/Y') : '?';
                                    $fechaFin = !empty($lactancia['fecha_fin']) ? \Carbon\Carbon::parse($lactancia['fecha_fin'])->format('d/m/Y') : 'En curso';
                                    $anNombre = data_get($lactancia, 'animal.nombre') ?? ('Animal #'.(data_get($lactancia, 'animal_id') ?? 'N/A'));
                                    $isSelected = (string)$lactanciaIdReg === (string)$lactId;
                                @endphp
                                @if($lactId)
                                    <option value="{{ $lactId }}" {{ $isSelected ? 'selected' : '' }}>
                                        {{ $anNombre }} — {{ $fechaInicio }} al {{ $fechaFin }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('lactancia_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Card 2: Campos Modificables -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🥛</span> Modificar Datos de Producción
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de Pesaje <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="fecha_pesaje" id="fecha_pesaje" required value="{{ $vFechaPesaje }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('fecha_pesaje') border-red-500 @enderror">
                            @error('fecha_pesaje')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Cantidad Producida (Litros) <span class="text-red-500">*</span>
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
                    <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>⚙️</span> Actualizar Registro
                        </h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="text-xs text-gray-500 space-y-2 border-b border-gray-100 pb-4">
                            <div class="flex justify-between">
                                <span>ID Registro:</span>
                                <span class="font-bold text-gray-900 font-mono">#{{ $lecheId }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Animal:</span>
                                <span class="font-bold text-gray-900">{{ $animalNombre }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Creado el:</span>
                                <span class="font-semibold text-gray-800">{{ isset($registroLeche['created_at']) ? date('d/m/Y H:i', strtotime($registroLeche['created_at'])) : 'N/A' }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                                💾 Actualizar Registro
                            </button>

                            <a href="{{ route('leche.index') }}"
                               class="w-full py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
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
