@extends('layouts.authenticated')

@section('title', 'Editar semen de toro')

@section('content')
@php
    $id = $semen['id'] ?? null;
    $toroId = $semen['animal_id'] ?? data_get($semen, 'toro.id') ?? '';
    $toroNombre = data_get($semen, 'toro.Nombre') ?? data_get($semen, 'toro.nombre') ?? ('Toro #'.$toroId);
    $toroCodigo = data_get($semen, 'toro.codigo_animal') ?? data_get($semen, 'toro.Codigo') ?? '';
    $toroRaza = data_get($semen, 'toro.composicion_raza.nombre') ?? data_get($semen, 'toro.composicionRaza.nombre') ?? data_get($semen, 'toro.raza.Nombre') ?? data_get($semen, 'toro.raza.nombre') ?? '';

    $fincaNombre = data_get($semen, 'toro.rebano.finca.Nombre') ?? data_get($semen, 'toro.rebano.finca.nombre') ?? '';
    $rebanoNombre = data_get($semen, 'toro.rebano.Nombre') ?? data_get($semen, 'toro.rebano.nombre') ?? '';

    $estado = old('estado', isset($semen['estado']) ? ($semen['estado'] ? '1' : '0') : '1');
    $fechaRaw = old('fecha', $semen['fecha'] ?? null);
    $fechaValue = '';
    if (!empty($fechaRaw)) {
        try {
            $fechaValue = \Carbon\Carbon::parse($fechaRaw)->format('Y-m-d');
        } catch (\Exception $e) {
            $fechaValue = '';
        }
    }
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center font-bold text-2xl shadow-xs border border-orange-100 shrink-0">
                🧬
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Editar semen de toro #{{ $id }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">Actualiza los datos y disponibilidad del lote de semen</p>
            </div>
        </div>
        <div>
            <a href="{{ route('semen-toro.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm space-y-1">
            <div class="flex items-center space-x-2 font-bold mb-1">
                <span class="text-lg">⚠️</span>
                <p class="text-sm">Por favor corrige los siguientes errores:</p>
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5 ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulario Principal -->
    <form method="POST" action="{{ route('semen-toro.update', $id) }}" id="formEditSemenToro" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Card 1: Toro Donante -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🐂</span> Toro donante
                    </h3>

                    <div class="p-5 bg-gray-50/90 border border-gray-200/80 rounded-2xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-700 border border-blue-100 font-bold flex items-center justify-center text-3xl shadow-xs shrink-0">
                                🐂
                            </div>
                            <div>
                                <p class="text-xl font-bold text-gray-900">{{ $toroNombre }}</p>
                                <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                    @if($toroCodigo)
                                        <span class="text-xs font-mono text-gray-600 bg-white px-2.5 py-0.5 rounded-md border border-gray-200 font-semibold">
                                            #{{ $toroCodigo }}
                                        </span>
                                    @endif
                                    @if($toroRaza)
                                        <span class="text-xs font-bold text-blue-800 bg-blue-50 px-2.5 py-0.5 rounded-md border border-blue-200">
                                            {{ $toroRaza }}
                                        </span>
                                    @endif
                                    @if($fincaNombre)
                                        <span class="text-xs font-semibold text-gray-700 bg-white px-2.5 py-0.5 rounded-md border border-gray-200">
                                            🏡 {{ $fincaNombre }}
                                        </span>
                                    @endif
                                    @if($rebanoNombre)
                                        <span class="text-xs font-semibold text-gray-700 bg-white px-2.5 py-0.5 rounded-md border border-gray-200">
                                            {{ $rebanoNombre }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($toroId)
                            <div>
                                <a href="{{ route('animales.show', $toroId) }}"
                                   class="px-5 py-2.5 bg-white hover:bg-gray-100 border border-gray-300 text-gray-800 font-semibold rounded-xl text-sm inline-flex items-center gap-2 transition-all shadow-xs hover:shadow-sm">
                                    <svg class="w-4 h-4 text-ganaderasoft-azul" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Ver ficha
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Card 2: Parámetros del Lote de Semen -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🧬</span> Parámetros del lote de pajuelas
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Estado / Disponibilidad -->
                        <div>
                            <label for="estado" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Disponibilidad en banco <span class="text-red-500">*</span>
                            </label>
                            <select name="estado" id="estado"
                                    class="w-full px-4 py-3 border @error('estado') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                                <option value="1" {{ (string)$estado === '1' ? 'selected' : '' }}>🟢 Disponible / Activo en banco</option>
                                <option value="0" {{ (string)$estado === '0' ? 'selected' : '' }}>⚪ Agotado / Inactivo</option>
                            </select>
                            @error('estado')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Fecha de Colecta / Ingreso -->
                        <div>
                            <label for="fecha" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de colecta / ingreso al banco
                            </label>
                            <input type="date" name="fecha" id="fecha"
                                   value="{{ $fechaValue }}"
                                   class="w-full px-4 py-3 border @error('fecha') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                            @error('fecha')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen de Ficha en Vivo (1 Tercio) -->
            <div class="space-y-6">
                <!-- Card 1: Resumen y Acciones -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>📋</span> Resumen del lote de semen
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Preview Avatar e Identificación -->
                        <div class="p-4 bg-teal-50/70 border border-teal-100 rounded-2xl flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-xl bg-white border border-teal-200 text-teal-700 font-bold flex items-center justify-center text-2xl shadow-xs shrink-0">
                                🐂
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-base font-bold text-gray-900 truncate">{{ $toroNombre }}</p>
                                <p class="text-xs text-gray-500 font-mono">Código: {{ $toroCodigo ? '#'.$toroCodigo : 'ID #'.$toroId }}</p>
                            </div>
                        </div>

                        <!-- Mini Stats Preview -->
                        <div class="space-y-3 text-xs text-gray-600 border-b border-gray-100 pb-4">
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Ubicación:</span>
                                <span class="font-bold text-gray-900 text-right truncate">
                                    {{ implode(' • ', array_filter([$fincaNombre, $rebanoNombre])) ?: 'No especificada' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Raza:</span>
                                <span class="font-bold text-blue-700 text-right">{{ $toroRaza ?: 'No especificada' }}</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Disponibilidad:</span>
                                <span id="previewEstado" class="font-bold text-emerald-700 text-right">Disponible / Activo</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Fecha de colecta:</span>
                                <span id="previewFecha" class="font-bold text-gray-900 text-right">
                                    {{ $fechaValue ? date('d/m/Y', strtotime($fechaValue)) : 'No especificada' }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons en el Sidebar -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                                💾 Actualizar registro de semen
                            </button>
                            <a href="{{ route('semen-toro.index') }}"
                               class="w-full py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Protocolos y Conservación de Semen -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
                    <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <span>💡</span> Manejo y conservación criogénica
                    </h4>
                    
                    <div class="p-3.5 bg-cyan-50 rounded-xl border border-cyan-200 text-xs text-cyan-900 space-y-1.5 leading-relaxed">
                        <strong class="block font-bold">Conservación de pajuelas:</strong>
                        <p>• Conservar en termo criogénico con nitrógeno líquido a <strong>-196°C</strong>.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const estadoSelect = document.getElementById('estado');
    const fechaInput = document.getElementById('fecha');

    const previewEstado = document.getElementById('previewEstado');
    const previewFecha = document.getElementById('previewFecha');

    function calculateDates() {
        const val = fechaInput.value;
        if (!val) {
            previewFecha.textContent = 'No especificada';
            return;
        }

        const parts = val.split('-');
        if (parts.length === 3) {
            const d = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
            previewFecha.textContent = ('0' + d.getDate()).slice(-2) + '/' + ('0' + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
        }
    }

    function updatePreview() {
        const eVal = estadoSelect.value;
        if (eVal === '1') {
            previewEstado.textContent = 'Disponible / Activo';
            previewEstado.className = 'font-bold text-emerald-700 text-right';
        } else {
            previewEstado.textContent = 'Agotado / Inactivo';
            previewEstado.className = 'font-bold text-gray-500 text-right';
        }

        calculateDates();
    }

    estadoSelect.addEventListener('change', updatePreview);
    fechaInput.addEventListener('input', calculateDates);

    updatePreview();
});
</script>
@endsection
