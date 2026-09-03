@extends('layouts.authenticated')

@section('title', 'Editar diagnóstico')

@section('content')
@php
    $id = $diagnostico['id'] ?? $diagnostico['diagnostico_id'] ?? null;
    $animalId = $diagnostico['animal_id'] ?? $diagnostico['fk_etapa_animal_anid'] ?? data_get($diagnostico, 'etapa_animal.animal_id');
    $animalRefId = data_get($diagnostico, 'animal.id') ?? data_get($diagnostico, 'animal.id_Animal') ?? data_get($diagnostico, 'etapa_animal.animal.id') ?? $animalId;
    $animalNombre = data_get($diagnostico, 'animal.Nombre') ?? data_get($diagnostico, 'animal.nombre') ?? data_get($diagnostico, 'etapa_animal.animal.nombre') ?? data_get($diagnostico, 'etapa_animal.animal.Nombre') ?? ('Animal #'.$animalId);
    
    $etapaId = $diagnostico['etapa_id'] ?? $diagnostico['fk_etapa_animal_etid'] ?? data_get($diagnostico, 'etapa_animal.etapa_id');
    $etapaNombre = data_get($diagnostico, 'etapa_animal.etapa.nombre') ?? data_get($diagnostico, 'etapa_animal.etapa.etapa_nombre') ?? data_get($diagnostico, 'etapa.nombre') ?? data_get($diagnostico, 'etapa.etapa_nombre') ?? ('Etapa #'.$etapaId);
    
    $tipo = $diagnostico['tipo'] ?? $diagnostico['diagnostico_tipo'] ?? '';
    
    $fechaRaw = old('fecha', $diagnostico['fecha'] ?? $diagnostico['diagnostico_fecha'] ?? null);
    $fechaValue = '';
    if (!empty($fechaRaw)) {
        try {
            $fechaValue = \Carbon\Carbon::parse($fechaRaw)->format('Y-m-d');
        } catch (\Exception $e) {
            $fechaValue = '';
        }
    }
    
    $descripcion = $diagnostico['descripcion'] ?? $diagnostico['diagnostico_descripcion'] ?? '';
    
    $sexoVal = data_get($diagnostico, 'animal.sexo') ?? data_get($diagnostico, 'animal.Sexo') ?? data_get($diagnostico, 'etapa_animal.animal.sexo') ?? data_get($diagnostico, 'etapa_animal.animal.Sexo') ?? 'H';
    $isMacho = in_array(strtoupper((string)$sexoVal), ['M', 'MACHO', 'MASCULINO']);
    
    $fincaNombre = data_get($diagnostico, 'animal.rebano.finca.Nombre') ?? data_get($diagnostico, 'animal.rebano.finca.nombre') ?? '';
    $rebanoNombre = data_get($diagnostico, 'animal.rebano.Nombre') ?? data_get($diagnostico, 'animal.rebano.nombre') ?? '';
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-azul/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl shadow-xs">
                🩺
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Editar diagnóstico #{{ $id }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">Actualiza la información médica, observaciones o fecha de evaluación</p>
            </div>
        </div>
        <div>
            <a href="{{ route('diagnostico.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Alert Errors -->
    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm space-y-2">
            <div class="flex items-center space-x-2">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-bold">Por favor corrige los siguientes errores:</p>
            </div>
            <ul class="list-disc list-inside text-xs space-y-1 pl-6">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('diagnostico.update', $id) }}" novalidate class="space-y-6" id="formEditDiagnostico">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Información del Animal y Etapa (Solo lectura) -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>📋</span> Animal y etapa clínica evaluada
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Animal Info Box -->
                        <div class="p-4 bg-gray-50 border border-gray-200/80 rounded-2xl flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-xl {{ $isMacho ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }} flex items-center justify-center text-2xl font-bold">
                                {{ $isMacho ? '🐂' : '🐄' }}
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Animal evaluado</p>
                                <p class="text-base font-bold text-gray-900 truncate">{{ $animalNombre }}</p>
                                <p class="text-xs text-gray-400">ID: #{{ $animalRefId }} {{ $rebanoNombre ? '• '.$rebanoNombre : ($fincaNombre ? '• '.$fincaNombre : '') }}</p>
                            </div>
                        </div>

                        <!-- Etapa Info Box -->
                        <div class="p-4 bg-gray-50 border border-gray-200/80 rounded-2xl flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-2xl font-bold">
                                🏷️
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Etapa productiva</p>
                                <p class="text-base font-bold text-gray-900 truncate">{{ $etapaNombre }}</p>
                                <p class="text-xs text-gray-400">ID Etapa: #{{ $etapaId ?: 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-3.5 bg-blue-50/70 border border-blue-200 rounded-xl text-xs text-blue-900 flex items-start gap-2.5">
                        <span class="text-base leading-none mt-0.5">ℹ️</span>
                        <p class="leading-relaxed font-medium">
                            El animal y la etapa quedan fijados históricamente para garantizar la trazabilidad médica. Si hubo un error en la selección del animal, se recomienda crear un nuevo registro.
                        </p>
                    </div>
                </div>

                <!-- Card 2: Datos Modificables del Diagnóstico -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🩺</span> Datos médicos del diagnóstico
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tipo" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Tipo de diagnóstico <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="tipo" name="tipo" value="{{ old('tipo', $tipo) }}" maxlength="30" required
                                   placeholder="Ej: Mastitis, Cojera, Neumonía..."
                                   class="w-full px-4 py-3 border @error('tipo') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('tipo')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="fecha" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de evaluación <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="fecha" name="fecha" value="{{ $fechaValue }}" required
                                   class="w-full px-4 py-3 border @error('fecha') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('fecha')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="descripcion" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Descripción y observaciones clínicas
                            </label>
                            <textarea id="descripcion" name="descripcion" rows="4"
                                      placeholder="Detalles del diagnóstico, evolución del animal o recomendaciones..."
                                      class="w-full px-4 py-3 border @error('descripcion') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">{{ old('descripcion', $descripcion) }}</textarea>
                            @error('descripcion')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen de Ficha en Vivo (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                    <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>📋</span> Resumen de la edición
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Preview Animal Avatar e Identificación -->
                        <div class="p-4 bg-blue-50/60 border border-blue-100 rounded-2xl flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-xl bg-white border border-blue-200 text-blue-700 font-bold flex items-center justify-center text-2xl shadow-xs">
                                {{ $isMacho ? '🐂' : '🐄' }}
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-base font-bold text-gray-900 truncate">{{ $animalNombre }}</p>
                                <p class="text-xs text-gray-500">ID: #{{ $animalRefId }}</p>
                            </div>
                        </div>

                        <!-- Mini Stats Preview -->
                        <div class="space-y-3 text-xs text-gray-600 border-b border-gray-100 pb-4">
                            @if($fincaNombre)
                                <div class="flex justify-between items-center">
                                    <span>Finca:</span>
                                    <span class="font-bold text-gray-900 truncate max-w-[150px]">{{ $fincaNombre }}</span>
                                </div>
                            @endif
                            @if($rebanoNombre)
                                <div class="flex justify-between items-center">
                                    <span>Rebaño:</span>
                                    <span class="font-bold text-gray-900 truncate max-w-[150px]">{{ $rebanoNombre }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-center">
                                <span>Etapa:</span>
                                <span class="font-bold text-gray-900 truncate max-w-[150px]">{{ $etapaNombre }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Tipo:</span>
                                <span id="previewTipo" class="font-bold text-ganaderasoft-verde-oscuro truncate max-w-[150px]">
                                    {{ $tipo ?: 'No especificado' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Fecha:</span>
                                <span id="previewFecha" class="font-bold text-gray-900">
                                    {{ $fechaValue ? date('d/m/Y', strtotime($fechaValue)) : 'No seleccionada' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Observaciones:</span>
                                <span id="previewDescripcionEstado" class="font-medium text-gray-400">
                                    {{ mb_strlen($descripcion) > 0 ? mb_strlen($descripcion).' caracteres' : 'Sin observaciones' }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2 cursor-pointer">
                                💾 Actualizar diagnóstico
                            </button>
                            <a href="{{ route('diagnostico.index') }}"
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
document.addEventListener('DOMContentLoaded', function () {
    const tipoInput = document.getElementById('tipo');
    const fechaInput = document.getElementById('fecha');
    const descripcionInput = document.getElementById('descripcion');

    const previewTipo = document.getElementById('previewTipo');
    const previewFecha = document.getElementById('previewFecha');
    const previewDescripcionEstado = document.getElementById('previewDescripcionEstado');

    function updatePreview() {
        previewTipo.textContent = tipoInput.value.trim() || 'No especificado';

        if (fechaInput.value) {
            const parts = fechaInput.value.split('-');
            if (parts.length === 3) {
                previewFecha.textContent = `${parts[2]}/${parts[1]}/${parts[0]}`;
            } else {
                previewFecha.textContent = fechaInput.value;
            }
        } else {
            previewFecha.textContent = 'No seleccionada';
        }

        const descLen = descripcionInput.value.trim().length;
        if (descLen > 0) {
            previewDescripcionEstado.textContent = `${descLen} caracteres`;
            previewDescripcionEstado.className = 'font-medium text-emerald-600';
        } else {
            previewDescripcionEstado.textContent = 'Sin observaciones';
            previewDescripcionEstado.className = 'font-medium text-gray-400';
        }
    }

    tipoInput.addEventListener('input', updatePreview);
    fechaInput.addEventListener('change', updatePreview);
    descripcionInput.addEventListener('input', updatePreview);

    updatePreview();
});
</script>
@endsection

