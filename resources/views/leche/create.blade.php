@extends('layouts.authenticated')

@section('title', 'Nuevo registro de leche')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl">
                🥛
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Nuevo pesaje de leche
                </h1>
                <p class="text-gray-500 text-sm mt-1">Registra la cantidad diaria de litros producida por una hembra en lactancia</p>
            </div>
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
    <form action="{{ route('leche.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Período de Lactancia -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🐄</span> Período de lactancia de la hembra
                    </h3>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                            Seleccionar lactancia <span class="text-red-500">*</span>
                        </label>
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
                                        {{ $animalNombre }} ({{ $animalCodigo ? '#'.$animalCodigo : 'Sin código' }}) — {{ $fechaInicio }} al {{ $fechaFin }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('lactancia_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Card 2: Datos de Producción -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🥛</span> Datos del pesaje lechero
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de pesaje <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="fecha_pesaje" id="fecha_pesaje" required value="{{ old('fecha_pesaje', date('Y-m-d')) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('fecha_pesaje') border-red-500 @enderror">
                            @error('fecha_pesaje')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
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
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>📋</span> Resumen del pesaje
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Preview Animal -->
                        <div class="p-4 bg-pink-50/60 border border-pink-100 rounded-2xl space-y-2">
                            <span class="text-xs font-bold text-pink-900 uppercase tracking-wider">Hembra / lactancia:</span>
                            <p id="previewAnimalNombre" class="text-base font-bold text-gray-900">No seleccionada</p>
                            <p id="previewAnimalCodigo" class="text-xs text-gray-500 font-mono">-</p>
                        </div>

                        <div class="space-y-3 text-xs text-gray-600 border-b border-gray-100 pb-4">
                            <div class="flex justify-between">
                                <span>Fecha pesaje:</span>
                                <span id="previewFecha" class="font-semibold text-gray-900">{{ date('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Volumen registrado:</span>
                                <span id="previewVolumen" class="font-extrabold text-emerald-600 text-base">0.00 L</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                                💾 Guardar registro
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const lactanciaSelect = document.getElementById('lactancia_id');
    const fechaInput     = document.getElementById('fecha_pesaje');
    const pesajeInput    = document.getElementById('pesaje_total');

    const previewNombre  = document.getElementById('previewAnimalNombre');
    const previewCodigo  = document.getElementById('previewAnimalCodigo');
    const previewFecha   = document.getElementById('previewFecha');
    const previewVolumen = document.getElementById('previewVolumen');

    function updateLivePreview() {
        const option = lactanciaSelect.options[lactanciaSelect.selectedIndex];
        if (!lactanciaSelect.value || !option) {
            previewNombre.textContent = 'No seleccionada';
            previewCodigo.textContent = '-';
        } else {
            previewNombre.textContent = option.dataset.nombre || 'Hembra seleccionada';
            previewCodigo.textContent = option.dataset.codigo ? '#' + option.dataset.codigo : '-';
        }

        if (fechaInput.value) {
            const parts = fechaInput.value.split('-');
            if (parts.length === 3) previewFecha.textContent = `${parts[2]}/${parts[1]}/${parts[0]}`;
        }

        const vol = parseFloat(pesajeInput.value || 0);
        previewVolumen.textContent = vol > 0 ? `${vol.toFixed(2)} L` : '0.00 L';
    }

    lactanciaSelect.addEventListener('change', updateLivePreview);
    fechaInput.addEventListener('change', updateLivePreview);
    pesajeInput.addEventListener('input', updateLivePreview);

    updateLivePreview();
});
</script>
@endsection