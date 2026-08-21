@extends('layouts.authenticated')

@section('title', 'Nuevo cambio de animal')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl shadow-sm border border-ganaderasoft-celeste/20">
                📝
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Nuevo cambio de animal
                </h1>
                <p class="text-gray-500 text-sm mt-1">Registra los cambios de desarrollo, etapa y medidas físicas del animal</p>
            </div>
        </div>
        <div>
            <a href="{{ route('cambios-animal.index') }}" 
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
    <form action="{{ route('cambios-animal.store') }}" method="POST" id="formCambioAnimal">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Selección del Animal -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🐄</span> Selección del animal
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Selector Animal -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                                Animal <span class="text-red-500">*</span>
                            </label>
                            <select name="animal_id" id="animal_id" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:bg-white transition-all @error('animal_id') border-red-500 @enderror">
                                <option value="">Seleccione un animal...</option>
                                @if(is_array($animales))
                                    @foreach($animales as $animal)
                                        @if(is_array($animal) && isset($animal['id']))
                                            @php
                                                $anPk = $animal['id'];
                                                $etapaActual = data_get($animal, 'etapa_actual', []);
                                                $etapaId = $etapaActual['etapa_id'] ?? data_get($etapaActual, 'etapa.id') ?? '';
                                                $animalEtapaId = $etapaActual['id'] ?? $etapaActual['animal_etapa_id'] ?? '';
                                                $etapaNombre = data_get($etapaActual, 'etapa.nombre')
                                                    ?? data_get($etapaActual, 'etapa.Nombre')
                                                    ?? ($etapaActual['nombre'] ?? null)
                                                    ?? ($etapaActual['descripcion'] ?? null)
                                                    ?? ($etapaId ? ('Etapa #'.$etapaId) : '');
                                                $razaNombre = data_get($animal, 'raza.nombre') ?? $animal['raza'] ?? 'No especificada';
                                                $rebanoNombre = data_get($animal, 'rebano.nombre') ?? 'No especificado';
                                                $fincaNombre = data_get($animal, 'rebano.finca.nombre') ?? data_get($animal, 'finca.nombre') ?? 'No especificada';
                                            @endphp
                                            <option value="{{ $anPk }}" {{ old('animal_id') == $anPk ? 'selected' : '' }}
                                                    data-nombre="{{ $animal['nombre'] ?? ('Animal #'.$anPk) }}"
                                                    data-codigo="{{ $animal['codigo_animal'] ?? '' }}"
                                                    data-sexo="{{ $animal['sexo'] ?? '' }}"
                                                    data-raza="{{ $razaNombre }}"
                                                    data-rebano="{{ $rebanoNombre }}"
                                                    data-finca="{{ $fincaNombre }}"
                                                    data-etapa-id="{{ $etapaId }}"
                                                    data-animal-etapa-id="{{ $animalEtapaId }}"
                                                    data-etapa-nombre="{{ $etapaNombre }}">
                                                {{ $animal['nombre'] ?? ('Animal #'.$anPk) }} ({{ $animal['codigo_animal'] ?? 'Sin código' }})
                                            </option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            @error('animal_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Etapa Actual Detectada -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                                Etapa actual activa
                            </label>
                            <input type="text" id="etapa_actual_texto" readonly
                                   placeholder="Seleccione un animal para consultar"
                                   class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 cursor-not-allowed">
                            <input type="hidden" name="animal_etapa_id" id="animal_etapa_id" value="{{ old('animal_etapa_id') }}">
                            <input type="hidden" name="etapa_id" id="etapa_id" value="{{ old('etapa_id') }}">
                        </div>
                    </div>
                </div>

                <!-- Card 2: Datos del Cambio y Medidas -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>📊</span> Nueva etapa y medidas físicas
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nueva Etapa -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                                Nueva etapa asignada <span class="text-red-500">*</span>
                            </label>
                            <select name="etapa_cambio" id="etapa_cambio" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:bg-white transition-all @error('etapa_cambio') border-red-500 @enderror">
                                <option value="">Seleccione la nueva etapa...</option>
                                @if(is_array($etapas) && count($etapas) > 0)
                                    @foreach($etapas as $etapa)
                                        @php
                                            $etNombre = is_array($etapa) ? ($etapa['nombre'] ?? $etapa['Nombre'] ?? '') : $etapa;
                                        @endphp
                                        <option value="{{ $etNombre }}" {{ old('etapa_cambio') == $etNombre ? 'selected' : '' }}>
                                            {{ $etNombre }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="Becerro" {{ old('etapa_cambio') == 'Becerro' ? 'selected' : '' }}>Becerro</option>
                                    <option value="Juvenil" {{ old('etapa_cambio') == 'Juvenil' ? 'selected' : '' }}>Juvenil</option>
                                    <option value="Adulto" {{ old('etapa_cambio') == 'Adulto' ? 'selected' : '' }}>Adulto</option>
                                @endif
                            </select>
                            @error('etapa_cambio')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Fecha del Cambio -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                                Fecha del cambio <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="fecha_cambio" id="fecha_cambio" required
                                   max="{{ date('Y-m-d') }}"
                                   value="{{ old('fecha_cambio', date('Y-m-d')) }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:bg-white transition-all @error('fecha_cambio') border-red-500 @enderror">
                            @error('fecha_cambio')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Peso (kg) -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                                Peso corporal (kg)
                            </label>
                            <div class="relative">
                                <input type="number" step="0.1" min="0" max="2000" name="peso" id="peso"
                                       placeholder="Ej: 350.5"
                                       value="{{ old('peso') }}"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:bg-white transition-all pr-12 @error('peso') border-red-500 @enderror">
                                <span class="absolute right-4 top-3.5 text-xs font-bold text-gray-400">kg</span>
                            </div>
                            @error('peso')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Altura (cm) -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                                Altura a la cruz (cm)
                            </label>
                            <div class="relative">
                                <input type="number" step="0.1" min="0" max="300" name="altura" id="altura"
                                       placeholder="Ej: 125.0"
                                       value="{{ old('altura') }}"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:bg-white transition-all pr-12 @error('altura') border-red-500 @enderror">
                                <span class="absolute right-4 top-3.5 text-xs font-bold text-gray-400">cm</span>
                            </div>
                            @error('altura')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 3: Comentarios -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                            <span>💬</span> Observaciones adicionales
                        </h3>
                        <span id="comentarioContador" class="text-xs text-gray-400 font-medium">0 / 500 caracteres</span>
                    </div>

                    <div>
                        <textarea name="comentario" id="comentario" rows="3" maxlength="500"
                                  placeholder="Notas sobre el estado físico, nutrición o motivo del cambio..."
                                  class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:bg-white transition-all resize-none">{{ old('comentario') }}</textarea>
                        @error('comentario')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Sidebar Resumen (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    <div class="bg-gray-50/80 border-b border-gray-100 px-6 py-4">
                        <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <span>📋</span> Resumen del cambio
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Ficha Animal Preview -->
                        <div class="p-4 bg-blue-50/60 border border-blue-100 rounded-2xl space-y-2">
                            <div class="flex items-center space-x-3">
                                <div id="previewAvatar" class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-lg border border-blue-200 shrink-0">
                                    🐄
                                </div>
                                <div class="overflow-hidden">
                                    <p id="previewAnimalNombre" class="text-sm font-bold text-gray-900 truncate">No seleccionado</p>
                                    <p id="previewAnimalCodigo" class="text-xs text-gray-400 font-mono">Seleccione un animal</p>
                                </div>
                            </div>
                            
                            <div class="text-xs space-y-1 border-t border-blue-100/60 pt-2 text-gray-600">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Raza:</span>
                                    <span id="previewRaza" class="font-semibold text-gray-800">--</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Rebaño:</span>
                                    <span id="previewRebano" class="font-semibold text-gray-800">--</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Finca:</span>
                                    <span id="previewFinca" class="font-semibold text-gray-800">--</span>
                                </div>
                            </div>
                        </div>

                        <!-- Mini Stats Preview -->
                        <div class="space-y-3 text-xs text-gray-600 border-b border-gray-100 pb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Etapa actual:</span>
                                <span id="previewEtapaActual" class="font-bold text-ganaderasoft-azul">Sin etapa</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Nueva etapa:</span>
                                <span id="previewNuevaEtapa" class="font-bold text-emerald-600">No especificada</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Peso ingresado:</span>
                                <span id="previewPeso" class="font-bold text-gray-900">--</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Altura ingresada:</span>
                                <span id="previewAltura" class="font-bold text-gray-900">--</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Fecha del registro:</span>
                                <span id="previewFecha" class="font-semibold text-gray-900">{{ date('d/m/Y') }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2 cursor-pointer">
                                <span>💾</span> Guardar cambio
                            </button>
                            <a href="{{ route('cambios-animal.index') }}"
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
    const animalSelect       = document.getElementById('animal_id');
    const animalEtapaInput   = document.getElementById('animal_etapa_id');
    const etapaIdInput       = document.getElementById('etapa_id');
    const etapaTexto         = document.getElementById('etapa_actual_texto');
    const etapaCambioSelect  = document.getElementById('etapa_cambio');

    const pesoInput          = document.getElementById('peso');
    const alturaInput        = document.getElementById('altura');
    const fechaInput         = document.getElementById('fecha_cambio');
    const comentarioInput    = document.getElementById('comentario');
    const comentarioContador = document.getElementById('comentarioContador');

    const previewNombre      = document.getElementById('previewAnimalNombre');
    const previewCodigo      = document.getElementById('previewAnimalCodigo');
    const previewAvatar      = document.getElementById('previewAvatar');
    const previewRaza        = document.getElementById('previewRaza');
    const previewRebano      = document.getElementById('previewRebano');
    const previewFinca       = document.getElementById('previewFinca');

    const previewEtapaActual = document.getElementById('previewEtapaActual');
    const previewNuevaEtapa  = document.getElementById('previewNuevaEtapa');
    const previewPeso        = document.getElementById('previewPeso');
    const previewAltura      = document.getElementById('previewAltura');
    const previewFecha       = document.getElementById('previewFecha');

    const endpointTemplate   = '{{ route('cambios-animal.animal.etapa', ['id' => '__ID__']) }}';

    function renderStage(option, fetchedData) {
        let etapaId = '', animalEtapaId = '', etapaNombre = '';

        if (fetchedData && fetchedData.etapa_actual) {
            const ea = fetchedData.etapa_actual;
            etapaId = ea.etapa_id || (ea.etapa && ea.etapa.id) || '';
            animalEtapaId = ea.id || ea.animal_etapa_id || '';
            etapaNombre = (ea.etapa && (ea.etapa.nombre || ea.etapa.Nombre))
                || ea.nombre || ea.Nombre || ea.descripcion || (etapaId ? ('Etapa #' + etapaId) : '');
        } else if (option) {
            etapaId = option.dataset.etapaId || '';
            animalEtapaId = option.dataset.animalEtapaId || '';
            etapaNombre = option.dataset.etapaNombre || '';
        }

        etapaIdInput.value = etapaId;
        animalEtapaInput.value = animalEtapaId;

        const displayTexto = etapaNombre || (etapaId ? ('Etapa #' + etapaId) : '');
        etapaTexto.value = displayTexto || 'Animal sin etapa activa';
        previewEtapaActual.textContent = displayTexto || 'Sin etapa';
    }

    async function updateStageAndPreview() {
        const option = animalSelect.options[animalSelect.selectedIndex];
        if (!animalSelect.value || !option) {
            etapaIdInput.value = '';
            animalEtapaInput.value = '';
            etapaTexto.value = '';
            previewNombre.textContent = 'No seleccionado';
            previewCodigo.textContent = 'Seleccione un animal';
            previewAvatar.textContent = '🐄';
            previewRaza.textContent = '--';
            previewRebano.textContent = '--';
            previewFinca.textContent = '--';
            previewEtapaActual.textContent = 'Sin etapa';
        } else {
            const nombre = option.dataset.nombre || 'Animal seleccionado';
            const codigo = option.dataset.codigo ? ('Código: #' + option.dataset.codigo) : ('ID: #' + animalSelect.value);
            const sexo = option.dataset.sexo || '';

            previewNombre.textContent = nombre;
            previewCodigo.textContent = codigo;
            previewAvatar.textContent = sexo === 'M' ? '🐂' : '🐄';
            previewRaza.textContent = option.dataset.raza || '--';
            previewRebano.textContent = option.dataset.rebano || '--';
            previewFinca.textContent = option.dataset.finca || '--';

            renderStage(option, null);

            if (!etapaIdInput.value || !etapaTexto.value || etapaTexto.value === 'Animal sin etapa activa') {
                try {
                    const response = await fetch(endpointTemplate.replace('__ID__', animalSelect.value), {
                        headers: { 'Accept': 'application/json' }
                    });
                    const payload = await response.json();
                    if (payload && payload.success && payload.data) {
                        renderStage(option, payload.data);
                    }
                } catch (error) {
                    console.error('Error al consultar etapa:', error);
                }
            }
        }

        // Live values preview
        previewNuevaEtapa.textContent = etapaCambioSelect.value.trim() || 'No especificada';
        previewPeso.textContent = pesoInput.value ? `${parseFloat(pesoInput.value).toFixed(1)} kg` : '--';
        previewAltura.textContent = alturaInput.value ? `${parseFloat(alturaInput.value).toFixed(1)} cm` : '--';

        if (fechaInput.value) {
            const parts = fechaInput.value.split('-');
            if (parts.length === 3) {
                previewFecha.textContent = `${parts[2]}/${parts[1]}/${parts[0]}`;
            }
        }

        if (comentarioInput && comentarioContador) {
            comentarioContador.textContent = `${comentarioInput.value.length} / 500 caracteres`;
        }
    }

    animalSelect.addEventListener('change', updateStageAndPreview);
    etapaCambioSelect.addEventListener('change', updateStageAndPreview);
    pesoInput.addEventListener('input', updateStageAndPreview);
    alturaInput.addEventListener('input', updateStageAndPreview);
    fechaInput.addEventListener('change', updateStageAndPreview);
    if (comentarioInput) comentarioInput.addEventListener('input', updateStageAndPreview);

    updateStageAndPreview();
});
</script>
@endsection