@extends('layouts.authenticated')

@section('title', 'Editar reproducción animal')

@section('content')
@php
    $id = $reproduccion['id'] ?? $reproduccion['repro_id'] ?? null;
    $animalId = $reproduccion['animal_id'] ?? $reproduccion['repro_etapa_anid'] ?? data_get($reproduccion, 'etapa_animal.animal_id');
    $animalRefId = data_get($reproduccion, 'animal.id') ?? data_get($reproduccion, 'animal.id_Animal') ?? $animalId;
    $animalNombre = data_get($reproduccion, 'animal.Nombre') ?? data_get($reproduccion, 'animal.nombre') ?? ('Animal #'.$animalId);
    $animalCodigo = data_get($reproduccion, 'animal.codigo_animal') ?? data_get($reproduccion, 'animal.Codigo') ?? '';
    
    $etapaId = (string)($reproduccion['etapa_id'] ?? $reproduccion['repro_etapa_etid'] ?? data_get($reproduccion, 'etapa_animal.etapa_id') ?? '');
    $etapaNombre = data_get($reproduccion, 'etapa_animal.etapa.nombre') 
        ?? data_get($reproduccion, 'etapa_animal.etapa.Nombre') 
        ?? data_get($reproduccion, 'etapa.nombre') 
        ?? data_get($reproduccion, 'etapa.Nombre') 
        ?? ($etapaId ? 'Etapa #'.$etapaId : 'En producción');

    $fincaNombre = data_get($reproduccion, 'etapa_animal.animal.rebano.finca.Nombre') ?? data_get($reproduccion, 'animal.rebano.finca.Nombre') ?? data_get($reproduccion, 'animal.rebano.finca.nombre') ?? '';
    $rebanoNombre = data_get($reproduccion, 'etapa_animal.animal.rebano.Nombre') ?? data_get($reproduccion, 'animal.rebano.Nombre') ?? data_get($reproduccion, 'animal.rebano.nombre') ?? '';

    $tipo = old('tipo', $reproduccion['tipo'] ?? $reproduccion['tipo_reproduccion'] ?? $reproduccion['repro_tipo_reproduccion'] ?? 'IA');
    $fechaRaw = old('fecha', $reproduccion['fecha'] ?? $reproduccion['fecha_reproduccion'] ?? $reproduccion['repro_fecha_reproduccion'] ?? null);
    $fechaValue = '';
    if (!empty($fechaRaw)) {
        try {
            $fechaValue = \Carbon\Carbon::parse($fechaRaw)->format('Y-m-d');
        } catch (\Exception $e) {
            $fechaValue = '';
        }
    }
    $observacion = old('observacion', $reproduccion['observacion'] ?? $reproduccion['repro_observacion'] ?? '');
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center font-bold text-2xl shadow-xs border border-orange-100 shrink-0">
                🔬
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Editar reproducción animal #{{ $id }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">Actualiza los datos del evento reproductivo de la hembra</p>
            </div>
        </div>
        <div>
            <a href="{{ route('reproduccion-animal.index') }}" 
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
    <form method="POST" action="{{ route('reproduccion-animal.update', $id) }}" id="formEditReproduccionAnimal" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 flex flex-col space-y-6">
                
                <!-- Card 1: Hembra Receptora -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🐄</span> Hembra receptora
                    </h3>

                    <div class="p-5 bg-gray-50/90 border border-gray-200/80 rounded-2xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 rounded-2xl bg-pink-50 text-pink-600 border border-pink-100 font-bold flex items-center justify-center text-3xl shadow-xs shrink-0">
                                🐄
                            </div>
                            <div>
                                <p class="text-xl font-bold text-gray-900">{{ $animalNombre }}</p>
                                <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                    @if($animalCodigo)
                                        <span class="text-xs font-mono text-gray-600 bg-white px-2.5 py-0.5 rounded-md border border-gray-200 font-semibold">
                                            #{{ $animalCodigo }}
                                        </span>
                                    @endif
                                    <span class="text-xs font-bold text-pink-800 bg-pink-50 px-2.5 py-0.5 rounded-md border border-pink-200">
                                        {{ $etapaNombre }}
                                    </span>
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

                        @if($animalRefId)
                            <div>
                                <a href="{{ route('animales.show', $animalRefId) }}"
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

                <!-- Card 2: Parámetros del Evento Reproductivo -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col flex-1 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🔬</span> Parámetros del evento reproductivo
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Etapa Productiva (Solo Lectura) -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Etapa productiva
                            </label>
                            <input type="text" readonly value="{{ $etapaNombre }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm bg-gray-50 text-gray-700 font-medium focus:outline-none">
                        </div>

                        <!-- Tipo de Evento -->
                        <div>
                            <label for="tipo" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Tipo de evento reproductivo <span class="text-red-500">*</span>
                            </label>
                            <select name="tipo" id="tipo" required
                                    class="w-full px-4 py-3 border @error('tipo') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                                <option value="IA" {{ strtolower((string)$tipo) === 'ia' ? 'selected' : '' }}>🧬 Inseminación Artificial (IA)</option>
                                <option value="Natural" {{ strtolower((string)$tipo) === 'natural' ? 'selected' : '' }}>🐂 Monta Natural</option>
                                <option value="Parto" {{ strtolower((string)$tipo) === 'parto' ? 'selected' : '' }}>🐣 Parto</option>
                                <option value="Aborto" {{ strtolower((string)$tipo) === 'aborto' ? 'selected' : '' }}>⚠️ Aborto</option>
                                <option value="Palpación" {{ strtolower((string)$tipo) === 'palpación' || strtolower((string)$tipo) === 'palpacion' ? 'selected' : '' }}>🩺 Palpación / Diagnóstico</option>
                            </select>
                            @error('tipo')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Fecha del Evento -->
                        <div class="md:col-span-2">
                            <label for="fecha" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha del evento <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="fecha" id="fecha" required 
                                   value="{{ $fechaValue }}"
                                   class="w-full px-4 py-3 border @error('fecha') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                            @error('fecha')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="flex-1 flex flex-col pt-2">
                        <label for="observacion" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                            Observaciones clínicas y notas reproductivas <span class="text-xs font-normal text-gray-400 normal-case">(opcional)</span>
                        </label>
                        <textarea name="observacion" id="observacion" rows="6" maxlength="100"
                                  placeholder="ej. Buena respuesta al servicio, revisión ginecológica favorable..."
                                  class="w-full flex-1 min-h-[160px] px-4 py-3 border @error('observacion') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white leading-relaxed">{{ $observacion }}</textarea>
                        <div class="flex justify-between items-center mt-1">
                            @error('observacion')
                                <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
                            @else
                                <span></span>
                            @enderror
                            <span class="text-[11px] text-gray-400">Máx. 100 caracteres</span>
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
                            <span>📋</span> Resumen del evento
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Preview Avatar e Identificación -->
                        <div class="p-4 bg-teal-50/70 border border-teal-100 rounded-2xl flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-xl bg-white border border-teal-200 text-teal-700 font-bold flex items-center justify-center text-2xl shadow-xs shrink-0">
                                🐄
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-base font-bold text-gray-900 truncate">{{ $animalNombre }}</p>
                                <p class="text-xs text-gray-500 font-mono">Código: {{ $animalCodigo ? '#'.$animalCodigo : 'ID #'.$animalRefId }}</p>
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
                                <span class="text-gray-500">Etapa actual:</span>
                                <span class="font-bold text-pink-700 text-right">{{ $etapaNombre }}</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Tipo de evento:</span>
                                <span id="previewTipo" class="font-bold text-blue-700 text-right">Inseminación (IA)</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Fecha del evento:</span>
                                <span id="previewFecha" class="font-bold text-gray-900 text-right">
                                    {{ $fechaValue ? date('d/m/Y', strtotime($fechaValue)) : 'No especificada' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Retorno celo (+21d):</span>
                                <span id="previewProximoCelo" class="font-bold text-purple-700 font-mono text-right">—</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Chequeo preñez (+45d):</span>
                                <span id="previewPalpacion" class="font-bold text-emerald-700 font-mono text-right">—</span>
                            </div>
                        </div>

                        <!-- Mensaje Proyección -->
                        <div class="p-3.5 bg-purple-50/70 border border-purple-100 rounded-xl space-y-1 text-xs text-purple-900 leading-relaxed">
                            <strong class="block font-bold">Proyección ginecológica:</strong>
                            <p>Supervisar retorno al celo a los <strong>+21 días</strong> post-evento. Programar palpación o ecografía a los <strong>+45 a +60 días</strong>.</p>
                        </div>

                        <!-- Action Buttons en el Sidebar -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                                💾 Actualizar registro reproductivo
                            </button>
                            <a href="{{ route('reproduccion-animal.index') }}"
                               class="w-full py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Guía y Buenas Prácticas Reproductivas -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
                    <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <span>💡</span> Buenas prácticas reproductivas
                    </h4>
                    
                    <div class="p-3.5 bg-amber-50 rounded-xl border border-amber-200 text-xs text-amber-900 space-y-1 leading-relaxed">
                        <strong class="block font-bold">Monitoreo del ciclo estral:</strong>
                        <p>• El ciclo estral bovino promedio es de <strong>21 días</strong> (rango 18-24 días).</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tipoSelect = document.getElementById('tipo');
    const fechaInput = document.getElementById('fecha');

    const previewTipo = document.getElementById('previewTipo');
    const previewFecha = document.getElementById('previewFecha');
    const previewProximoCelo = document.getElementById('previewProximoCelo');
    const previewPalpacion = document.getElementById('previewPalpacion');

    function calculateDates() {
        const val = fechaInput.value;
        if (!val) {
            previewFecha.textContent = 'No especificada';
            previewProximoCelo.textContent = '—';
            previewPalpacion.textContent = '—';
            return;
        }

        const parts = val.split('-');
        if (parts.length === 3) {
            const d = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
            previewFecha.textContent = ('0' + d.getDate()).slice(-2) + '/' + ('0' + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();

            // +21 days
            const nextCelo = new Date(d);
            nextCelo.setDate(nextCelo.getDate() + 21);
            previewProximoCelo.textContent = ('0' + nextCelo.getDate()).slice(-2) + '/' + ('0' + (nextCelo.getMonth() + 1)).slice(-2) + '/' + nextCelo.getFullYear();

            // +45 days
            const nextPalp = new Date(d);
            nextPalp.setDate(nextPalp.getDate() + 45);
            previewPalpacion.textContent = ('0' + nextPalp.getDate()).slice(-2) + '/' + ('0' + (nextPalp.getMonth() + 1)).slice(-2) + '/' + nextPalp.getFullYear();
        }
    }

    function updatePreview() {
        const tVal = tipoSelect.value;
        if (tVal === 'IA') {
            previewTipo.textContent = 'Inseminación (IA)';
            previewTipo.className = 'font-bold text-blue-700 text-right';
        } else if (tVal === 'Natural') {
            previewTipo.textContent = 'Monta Natural';
            previewTipo.className = 'font-bold text-emerald-700 text-right';
        } else if (tVal === 'Parto') {
            previewTipo.textContent = 'Parto';
            previewTipo.className = 'font-bold text-purple-700 text-right';
        } else if (tVal === 'Aborto') {
            previewTipo.textContent = 'Aborto';
            previewTipo.className = 'font-bold text-red-700 text-right';
        } else {
            previewTipo.textContent = tVal || 'Evento';
            previewTipo.className = 'font-bold text-gray-700 text-right';
        }

        calculateDates();
    }

    tipoSelect.addEventListener('change', updatePreview);
    fechaInput.addEventListener('input', calculateDates);

    updatePreview();
});
</script>
@endsection
