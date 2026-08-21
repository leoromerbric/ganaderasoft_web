@extends('layouts.authenticated')

@section('title', 'Editar lactancia')

@section('content')
@php
    $lactanciaId = $lactancia['id'] ?? null;
    $animalNombre = data_get($lactancia, 'animal.nombre') ?? ('Animal #'.(data_get($lactancia, 'animal_id') ?? 'N/A'));
    $animalCodigo = data_get($lactancia, 'animal.codigo_animal') ?? '';
    $etapaActual = data_get($lactancia, 'etapa.nombre')
        ?? data_get($lactancia, 'etapa_animal.etapa.nombre')
        ?? data_get($lactancia, 'animal.etapa_actual.nombre') ?? 'No disponible';

    $vFechaInicio = old('fecha_inicio') ?: (data_get($lactancia, 'fecha_inicio') ? substr(data_get($lactancia, 'fecha_inicio'), 0, 10) : date('Y-m-d'));
    $vFechaFin = old('fecha_fin') ?: (data_get($lactancia, 'fecha_fin') ? substr(data_get($lactancia, 'fecha_fin'), 0, 10) : '');
    $vSecado = old('secado') ?: (data_get($lactancia, 'secado') ? substr(data_get($lactancia, 'secado'), 0, 10) : '');

    $isActiva = empty($vFechaFin) || strtotime($vFechaFin) > time();
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl">
                ✏️
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Editar período de lactancia #{{ $lactanciaId ?? 'N/A' }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">Actualiza las fechas de inicio, fin o secado del ciclo productivo</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('lactancia.show', $lactanciaId) }}"
               class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Ver detalle
            </a>
            <a href="{{ route('lactancia.index') }}" 
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
    <form action="{{ route('lactancia.update', $lactanciaId) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="animal_id" value="{{ old('animal_id', data_get($lactancia, 'animal_id')) }}">
        <input type="hidden" name="etapa_id" value="{{ old('etapa_id', data_get($lactancia, 'etapa_id')) }}">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Animal Asociado (Inmutable) -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                            <span>🐄</span> Animal asociado
                        </h3>
                        <span class="text-xs font-semibold px-3 py-1 bg-gray-100 text-gray-600 rounded-full border border-gray-200">
                            🔒 Hembra inmutable
                        </span>
                    </div>

                    <div class="p-5 bg-pink-50/60 border border-pink-100 rounded-2xl flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-xl bg-white border border-pink-200 text-pink-700 font-bold flex items-center justify-center text-2xl shadow-xs">
                                🐄
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-900">{{ $animalNombre }}</p>
                                @if($animalCodigo)
                                    <p class="text-xs text-gray-500 font-mono">Código: #{{ $animalCodigo }}</p>
                                @endif
                                <p class="text-xs text-gray-500 font-semibold mt-1">Etapa: {{ $etapaActual }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Campos Modificables -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>📅</span> Modificar fechas del ciclo
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de inicio <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="fecha_inicio" required value="{{ $vFechaInicio }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('fecha_inicio') border-red-500 @enderror">
                            @error('fecha_inicio')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Fecha de fin (opcional)</label>
                            <input type="date" name="fecha_fin" value="{{ $vFechaFin }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('fecha_fin') border-red-500 @enderror">
                            <p class="text-xs text-gray-400 mt-1">Vacío si está activa.</p>
                            @error('fecha_fin')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Fecha de secado (opcional)</label>
                            <input type="date" name="secado" value="{{ $vSecado }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('secado') border-red-500 @enderror">
                            <p class="text-xs text-gray-400 mt-1">Preparación de secado.</p>
                            @error('secado')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Panel de Guardado (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>⚙️</span> Actualizar registro
                        </h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="text-xs text-gray-500 space-y-2 border-b border-gray-100 pb-4">
                            <div class="flex justify-between">
                                <span>ID lactancia:</span>
                                <span class="font-bold text-gray-900 font-mono">#{{ $lactanciaId }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Fecha registro:</span>
                                <span class="font-semibold text-gray-800">{{ isset($lactancia['created_at']) ? date('d/m/Y H:i', strtotime($lactancia['created_at'])) : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-1">
                                <span>Estado ciclo:</span>
                                @if($isActiva)
                                    <span class="font-bold text-emerald-600">🟢 Activa</span>
                                @else
                                    <span class="font-bold text-gray-600">⚪ Finalizada</span>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                                💾 Actualizar lactancia
                            </button>

                            <a href="{{ route('lactancia.index') }}"
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
