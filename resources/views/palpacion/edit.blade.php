@extends('layouts.authenticated')

@section('title', 'Editar palpación animal')

@section('content')
@php
    $id = $palpacion['id'] ?? $palpacion['palpacion_id'] ?? null;
    $animalId = $palpacion['animal_id'] ?? $palpacion['palpacion_etapa_anid'] ?? data_get($palpacion, 'etapa_animal.animal_id');
    $animalRefId = data_get($palpacion, 'animal.id') ?? data_get($palpacion, 'animal.id_Animal') ?? $animalId;
    $animalNombre = data_get($palpacion, 'animal.Nombre') ?? data_get($palpacion, 'animal.nombre') ?? ('Animal #'.$animalId);
    $animalCodigo = data_get($palpacion, 'animal.codigo_animal') ?? data_get($palpacion, 'animal.Codigo') ?? '';
    
    $etapaId = (string)($palpacion['etapa_id'] ?? $palpacion['palpacion_etapa_etid'] ?? data_get($palpacion, 'etapa_animal.etapa_id') ?? '');
    $etapaNombre = data_get($palpacion, 'etapa_animal.etapa.nombre') 
        ?? data_get($palpacion, 'etapa_animal.etapa.Nombre') 
        ?? data_get($palpacion, 'etapa.nombre') 
        ?? data_get($palpacion, 'etapa.Nombre') 
        ?? ($etapaId ? 'Etapa #'.$etapaId : 'En producción');

    $fincaNombre = data_get($palpacion, 'etapa_animal.animal.rebano.finca.Nombre') ?? data_get($palpacion, 'animal.rebano.finca.Nombre') ?? data_get($palpacion, 'animal.rebano.finca.nombre') ?? '';
    $rebanoNombre = data_get($palpacion, 'etapa_animal.animal.rebano.Nombre') ?? data_get($palpacion, 'animal.rebano.Nombre') ?? data_get($palpacion, 'animal.rebano.nombre') ?? '';

    $tecnicoId = $palpacion['tecnico_id'] ?? $palpacion['personal_finca_id'] ?? $palpacion['id_Tecnico'] ?? null;
    $tipo = old('tipo', $palpacion['tipo'] ?? $palpacion['palpacion_tipo'] ?? 'Preñez');
    
    $fechaRaw = old('fecha', $palpacion['fecha'] ?? $palpacion['palpacion_fecha'] ?? null);
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
                🩺
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Editar palpación animal #{{ $id }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">Actualiza los datos y resultado de la evaluación ginecológica</p>
            </div>
        </div>
        <div>
            <a href="{{ route('palpacion.index') }}" 
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
    <form method="POST" action="{{ route('palpacion.update', $id) }}" id="formEditPalpacionAnimal" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Card 1: Hembra Evaluada -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🐄</span> Hembra evaluada
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

                <!-- Card 2: Parámetros del Diagnóstico Clínico -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🩺</span> Parámetros del diagnóstico clínico
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

                        <!-- Tipo de Palpación / Resultado -->
                        <div>
                            <label for="tipo" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Resultado / Diagnóstico <span class="text-red-500">*</span>
                            </label>
                            <select name="tipo" id="tipo" required
                                    class="w-full px-4 py-3 border @error('tipo') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                                <option value="Preñez" {{ strtolower((string)$tipo) === 'preñez' || strtolower((string)$tipo) === 'prenez' || strtolower((string)$tipo) === 'preñada' ? 'selected' : '' }}>🤰 Preñada / Gestante</option>
                                <option value="Vacía" {{ strtolower((string)$tipo) === 'vacía' || strtolower((string)$tipo) === 'vacia' ? 'selected' : '' }}>⭕ Vacía / Abierta</option>
                                <option value="Revision" {{ strtolower((string)$tipo) === 'revision' || strtolower((string)$tipo) === 'revisión' ? 'selected' : '' }}>🩺 Revisión ginecológica</option>
                                <option value="Ecografía" {{ strtolower((string)$tipo) === 'ecografía' || strtolower((string)$tipo) === 'ecografia' ? 'selected' : '' }}>🔬 Ecografía</option>
                            </select>
                            @error('tipo')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Técnico / Veterinario Responsable -->
                        <div>
                            <label for="tecnico_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Veterinario / Técnico evaluador <span class="text-xs font-normal text-gray-400 normal-case">(opcional)</span>
                            </label>
                            <select name="tecnico_id" id="tecnico_id"
                                    class="w-full px-4 py-3 border @error('tecnico_id') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                                <option value="">-- Sin técnico asignado --</option>
                                @foreach($personal as $persona)
                                    @php
                                        $pId = data_get($persona, 'id') ?? data_get($persona, 'id_Tecnico') ?? data_get($persona, 'id_Personal') ?? data_get($persona, 'personal.id');
                                        $pNom = trim((data_get($persona, 'persona.nombre') ?? data_get($persona, 'Nombre') ?? data_get($persona, 'nombre') ?? data_get($persona, 'personal.Nombre') ?? '') . ' ' . (data_get($persona, 'persona.apellido') ?? data_get($persona, 'Apellido') ?? ''));
                                        $pCargo = data_get($persona, 'tipo_trabajador.nombre') ?? data_get($persona, 'tipoTrabajador.nombre') ?? data_get($persona, 'Tipo_Trabajador') ?? 'Veterinario';
                                    @endphp
                                    @continue(!$pId)
                                    <option value="{{ $pId }}" data-nombre="{{ $pNom }}" {{ (string)old('tecnico_id', $tecnicoId) === (string)$pId ? 'selected' : '' }}>
                                        👨‍⚕️ {{ $pNom ?: ('Personal #'.$pId) }} ({{ $pCargo }})
                                    </option>
                                @endforeach
                            </select>
                            @error('tecnico_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Fecha de la Palpación -->
                        <div>
                            <label for="fecha" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de la evaluación <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="fecha" id="fecha" required 
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
                            <span>📋</span> Resumen del diagnóstico
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
                                <span class="text-gray-500">Diagnóstico:</span>
                                <span id="previewTipo" class="font-bold text-emerald-700 text-right">Preñada / Gestante</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Evaluador:</span>
                                <span id="previewTecnico" class="font-bold text-gray-900 text-right truncate">Sin asignar</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Fecha evaluación:</span>
                                <span id="previewFecha" class="font-bold text-gray-900 text-right">
                                    {{ $fechaValue ? date('d/m/Y', strtotime($fechaValue)) : 'No especificada' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center gap-2" id="boxPartoEstimado">
                                <span class="text-gray-500">Parto est. (+283d):</span>
                                <span id="previewParto" class="font-bold text-emerald-700 font-mono text-right">—</span>
                            </div>
                            <div class="flex justify-between items-center gap-2" id="boxSecadoEstimado">
                                <span class="text-gray-500">Secado est. (+220d):</span>
                                <span id="previewSecado" class="font-bold text-purple-700 font-mono text-right">—</span>
                            </div>
                        </div>

                        <!-- Action Buttons en el Sidebar -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                                💾 Actualizar registro de palpación
                            </button>
                            <a href="{{ route('palpacion.index') }}"
                               class="w-full py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Guía Diagnóstica y Protocolos Reproductivos -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
                    <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <span>💡</span> Protocolos de diagnóstico reproductivo
                    </h4>
                    
                    <div class="p-3.5 bg-amber-50 rounded-xl border border-amber-200 text-xs text-amber-900 space-y-1 leading-relaxed">
                        <strong class="block font-bold">Ventanas de diagnóstico:</strong>
                        <p>• <strong>Palpación rectal:</strong> Certera a partir del <strong>día 45 - 60</strong> post-servicio.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tipoSelect = document.getElementById('tipo');
    const tecnicoSelect = document.getElementById('tecnico_id');
    const fechaInput = document.getElementById('fecha');

    const previewTipo = document.getElementById('previewTipo');
    const previewTecnico = document.getElementById('previewTecnico');
    const previewFecha = document.getElementById('previewFecha');
    const previewParto = document.getElementById('previewParto');
    const previewSecado = document.getElementById('previewSecado');
    const boxParto = document.getElementById('boxPartoEstimado');
    const boxSecado = document.getElementById('boxSecadoEstimado');

    function calculateDates() {
        const val = fechaInput.value;
        const tVal = (tipoSelect ? tipoSelect.value : '').toLowerCase();
        const isPrenada = tVal.includes('preñ') || tVal.includes('gestan') || tVal.includes('positiv');

        if (!val) {
            previewFecha.textContent = 'No especificada';
            previewParto.textContent = '—';
            previewSecado.textContent = '—';
            return;
        }

        const parts = val.split('-');
        if (parts.length === 3) {
            const d = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
            previewFecha.textContent = ('0' + d.getDate()).slice(-2) + '/' + ('0' + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();

            if (isPrenada) {
                const partoDate = new Date(d);
                partoDate.setDate(partoDate.getDate() + 283);
                if (previewParto) previewParto.textContent = ('0' + partoDate.getDate()).slice(-2) + '/' + ('0' + (partoDate.getMonth() + 1)).slice(-2) + '/' + partoDate.getFullYear();

                const secadoDate = new Date(d);
                secadoDate.setDate(secadoDate.getDate() + 220);
                if (previewSecado) previewSecado.textContent = ('0' + secadoDate.getDate()).slice(-2) + '/' + ('0' + (secadoDate.getMonth() + 1)).slice(-2) + '/' + secadoDate.getFullYear();

                if (boxParto) boxParto.style.display = 'flex';
                if (boxSecado) boxSecado.style.display = 'flex';
            } else {
                if (previewParto) previewParto.textContent = 'N/A';
                if (previewSecado) previewSecado.textContent = 'N/A';
                if (boxParto) boxParto.style.display = 'none';
                if (boxSecado) boxSecado.style.display = 'none';
            }
        }
    }

    function updatePreview() {
        const tVal = tipoSelect.value;
        if (tVal.toLowerCase().includes('preñ') || tVal.toLowerCase().includes('gestan')) {
            previewTipo.textContent = 'Preñada / Gestante';
            previewTipo.className = 'font-bold text-emerald-700 text-right';
        } else if (tVal.toLowerCase().includes('vac')) {
            previewTipo.textContent = 'Vacía / Abierta';
            previewTipo.className = 'font-bold text-amber-600 text-right';
        } else if (tVal.toLowerCase().includes('eco')) {
            previewTipo.textContent = 'Ecografía';
            previewTipo.className = 'font-bold text-purple-700 text-right';
        } else {
            previewTipo.textContent = tVal || 'Revisión';
            previewTipo.className = 'font-bold text-blue-700 text-right';
        }

        const tecOpt = tecnicoSelect ? tecnicoSelect.options[tecnicoSelect.selectedIndex] : null;
        if (previewTecnico) {
            previewTecnico.textContent = (tecOpt && tecOpt.value) ? (tecOpt.dataset.nombre || tecOpt.textContent.replace(/👨‍⚕️|\(.*\)/g, '').trim()) : 'Sin asignar';
        }

        calculateDates();
    }

    tipoSelect.addEventListener('change', updatePreview);
    tecnicoSelect.addEventListener('change', updatePreview);
    fechaInput.addEventListener('input', calculateDates);

    updatePreview();
});
</script>
@endsection
