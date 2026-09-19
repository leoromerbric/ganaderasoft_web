@extends('layouts.authenticated')

@section('title', 'Nuevo pesaje de leche')

@section('content')
<div class="space-y-8">
    <!-- Header section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">Nuevo pesaje de leche</h1>
            <p class="text-gray-500 text-sm mt-1">Registra la cantidad diaria de litros producida por una hembra en lactancia</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
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
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm space-y-2">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p class="text-sm font-bold">Por favor corrige los siguientes errores:</p>
            </div>
            <ul class="list-disc list-inside text-sm pl-6 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Alerta Módulo Prelante: Períodos de Lactancia Requeridos -->
    @php
        $hasLactancias = !empty($lactancias) && count($lactancias) > 0;
    @endphp

    @if(!$hasLactancias)
        <div class="p-5 bg-amber-50/90 border-l-4 border-amber-500 rounded-2xl shadow-sm space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start sm:items-center space-x-3.5">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-amber-950">No hay períodos de lactancia activos</h4>
                        <p class="text-sm text-amber-900 mt-0.5">
                            Para registrar un pesaje de leche, es necesario que la hembra cuente previamente con un ciclo de lactancia registrado en el sistema.
                        </p>
                    </div>
                </div>
                <div class="shrink-0">
                    <a href="{{ route('lactancia.create') }}" 
                       class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 min-h-[48px] bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl text-sm transition-all shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Registrar período de lactancia</span>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Container -->
    <form action="{{ route('leche.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Período de Lactancia -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <div class="flex items-center space-x-3 border-b border-gray-100 pb-4">
                        <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-600 font-bold flex items-center justify-center text-base border border-pink-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-ganaderasoft-negro">Período de lactancia</h3>
                    </div>

                    <div>
                        <label for="lactancia_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Hembra y ciclo productivo <span class="text-red-500">*</span>
                        </label>
                        @if(!$hasLactancias)
                            <div class="p-6 bg-gray-50 border border-dashed border-gray-300 rounded-2xl text-center space-y-3">
                                <div class="w-12 h-12 mx-auto rounded-full bg-amber-100 text-amber-700 flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-base font-bold text-gray-800">No se encontraron ciclos de lactancia registrados</p>
                                    <p class="text-sm text-gray-500 mt-1 max-w-md mx-auto">Para ingresar pesajes de producción lechera, primero debe registrar el ciclo de lactancia de la hembra.</p>
                                </div>
                                <div class="pt-1">
                                    <a href="{{ route('lactancia.create') }}" 
                                       class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 min-h-[48px] bg-ganaderasoft-azul hover:bg-opacity-90 text-white font-bold rounded-xl text-sm transition-all shadow-md hover:shadow-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        <span>Ir al módulo de lactancia (prelante)</span>
                                    </a>
                                </div>
                            </div>
                        @else
                            <select name="lactancia_id" id="lactancia_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('lactancia_id') border-red-500 @enderror">
                                <option value="">Seleccione un período de lactancia...</option>
                                @foreach($lactancias as $lactancia)
                                    @php
                                        $lactId = $lactancia['id'] ?? null;
                                        $fechaInicio = isset($lactancia['fecha_inicio']) ? \Carbon\Carbon::parse($lactancia['fecha_inicio'])->format('d/m/Y') : '?';
                                        $fechaFin = !empty($lactancia['fecha_fin']) ? \Carbon\Carbon::parse($lactancia['fecha_fin'])->format('d/m/Y') : 'En curso';
                                        $animalNombre = data_get($lactancia, 'animal.nombre') ?? ('Animal #'.(data_get($lactancia, 'animal_id') ?? 'N/A'));
                                        $animalCodigo = data_get($lactancia, 'animal.codigo_animal') ?? '';
                                        $isSelected = old('lactancia_id', $lactanciaId) == $lactId;
                                    @endphp
                                    @if($lactId)
                                        <option value="{{ $lactId }}" {{ $isSelected ? 'selected' : '' }}
                                                data-nombre="{{ $animalNombre }}"
                                                data-codigo="{{ $animalCodigo }}">
                                            {{ $animalNombre }} ({{ $animalCodigo ? '#'.$animalCodigo : 'ID #'.$lactId }}) — {{ $fechaInicio }} al {{ $fechaFin }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('lactancia_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        @endif
                    </div>
                </div>

                <!-- Card 2: Datos de Producción -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <div class="flex items-center space-x-3 border-b border-gray-100 pb-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 font-bold flex items-center justify-center text-base border border-emerald-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-ganaderasoft-negro">Datos del pesaje lechero</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="fecha_pesaje" class="block text-sm font-semibold text-gray-700 mb-2">
                                Fecha de pesaje <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="fecha_pesaje" id="fecha_pesaje" required 
                                   max="{{ date('Y-m-d') }}"
                                   value="{{ old('fecha_pesaje', date('Y-m-d')) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('fecha_pesaje') border-red-500 @enderror">
                            @error('fecha_pesaje')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="pesaje_total" class="block text-sm font-semibold text-gray-700 mb-2">
                                Cantidad producida (litros) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="pesaje_total" id="pesaje_total" required value="{{ old('pesaje_total') }}"
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

            <!-- Columna Derecha: Resumen en Vivo (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                    <div class="bg-gray-50/80 border-b border-gray-100 px-6 py-4">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h3 class="text-lg font-bold text-gray-900">Resumen del pesaje</h3>
                        </div>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Preview Animal -->
                        <div class="p-4 bg-pink-50/70 border border-pink-100 rounded-xl space-y-1">
                            <span class="text-xs font-bold text-pink-900 uppercase tracking-wider">Hembra / ciclo:</span>
                            <p id="previewAnimalNombre" class="text-base font-bold text-gray-900">No seleccionada</p>
                            <p id="previewAnimalCodigo" class="text-sm text-gray-500 font-mono">-</p>
                        </div>

                        <div class="space-y-3 text-sm text-gray-600 border-b border-gray-100 pb-4">
                            <div class="flex justify-between items-center">
                                <span>Fecha pesaje:</span>
                                <span id="previewFecha" class="font-bold text-gray-900">{{ date('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Volumen registrado:</span>
                                <span id="previewVolumen" class="font-extrabold text-emerald-600 text-base">0.00 L</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            @if(!$hasLactancias)
                                <button type="button" disabled
                                        class="w-full py-3.5 min-h-[48px] bg-gray-200 text-gray-500 font-bold rounded-xl text-sm flex items-center justify-center gap-2.5 cursor-not-allowed">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    Requiere ciclo de lactancia
                                </button>
                                <a href="{{ route('lactancia.create') }}"
                                   class="w-full py-3.5 min-h-[48px] bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl transition-all text-sm flex items-center justify-center gap-2.5 shadow-md hover:shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <span>Registrar período de lactancia</span>
                                </a>
                            @else
                                <button type="submit"
                                        class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                    </svg>
                                    Guardar pesaje
                                </button>
                            @endif
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const lactanciaSelect = document.getElementById('lactancia_id');
    const fechaInput     = document.getElementById('fecha_pesaje');
    const pesajeInput    = document.getElementById('pesaje_total');

    const previewNombre  = document.getElementById('previewAnimalNombre');
    const previewCodigo  = document.getElementById('previewAnimalCodigo');
    const previewFecha   = document.getElementById('previewFecha');
    const previewVolumen = document.getElementById('previewVolumen');

    function actualizarPreview() {
        if (lactanciaSelect && lactanciaSelect.selectedIndex > 0) {
            const selectedOpt = lactanciaSelect.options[lactanciaSelect.selectedIndex];
            previewNombre.textContent = selectedOpt.dataset.nombre || 'Hembra seleccionada';
            previewCodigo.textContent = selectedOpt.dataset.codigo ? '#' + selectedOpt.dataset.codigo : 'ID: #' + selectedOpt.value;
        } else {
            previewNombre.textContent = 'No seleccionada';
            previewCodigo.textContent = '-';
        }

        if (fechaInput && fechaInput.value) {
            const parts = fechaInput.value.split('-');
            if (parts.length === 3) {
                previewFecha.textContent = `${parts[2]}/${parts[1]}/${parts[0]}`;
            }
        }

        if (pesajeInput) {
            const vol = parseFloat(pesajeInput.value || 0);
            previewVolumen.textContent = vol.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' L';
        }
    }

    if (lactanciaSelect) lactanciaSelect.addEventListener('change', actualizarPreview);
    if (fechaInput) fechaInput.addEventListener('input', actualizarPreview);
    if (pesajeInput) pesajeInput.addEventListener('input', actualizarPreview);

    actualizarPreview();
});
</script>
@endsection