@extends('layouts.authenticated')

@section('title', 'Nuevo Cambio de Animal')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl">
                📝
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Nuevo Cambio de Animal
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
    <form action="{{ route('cambios-animal.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Selección del Animal -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🐄</span> Selección del Animal
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Animal <span class="text-red-500">*</span>
                            </label>
                            <select name="animal_id" id="animal_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('animal_id') border-red-500 @enderror">
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
                                            @endphp
                                            <option value="{{ $anPk }}" {{ old('animal_id') == $anPk ? 'selected' : '' }}
                                                    data-nombre="{{ $animal['nombre'] ?? ('Animal #'.$anPk) }}"
                                                    data-codigo="{{ $animal['codigo_animal'] ?? '' }}"
                                                    data-sexo="{{ $animal['sexo'] ?? '' }}"
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

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Etapa Actual del Animal
                            </label>
                            <input type="text" id="etapa_actual_texto" readonly
                                   class="w-full px-4 py-3 border border-gray-200 bg-gray-50 text-gray-600 rounded-xl text-sm font-semibold"
                                   placeholder="Se completará al seleccionar el animal">
                            <input type="hidden" name="animal_etapa_id" id="animal_etapa_id" value="{{ old('animal_etapa_id') }}">
                            <input type="hidden" name="etapa_id" id="etapa_id" value="{{ old('etapa_id') }}">
                        </div>
                    </div>
                </div>

                <!-- Card 2: Datos del Cambio -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🔄</span> Registro de Etapa
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Nombre de la Nueva Etapa <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="etapa_cambio" id="etapa_cambio" value="{{ old('etapa_cambio') }}" required
                                   placeholder="Ej: Becerro, Juvenil, Adulto"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('etapa_cambio') border-red-500 @enderror">
                            @error('etapa_cambio')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha del Cambio <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="fecha_cambio" id="fecha_cambio" value="{{ old('fecha_cambio', date('Y-m-d')) }}" required
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('fecha_cambio') border-red-500 @enderror">
                            @error('fecha_cambio')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 3: Medidas Físicas y Observaciones (Opcional) -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>⚖️</span> Medidas Físicas y Observaciones
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Peso Corporal (kg)
                            </label>
                            <div class="relative">
                                <input type="number" name="peso" id="peso" value="{{ old('peso') }}"
                                       step="0.1" min="1" max="2000" placeholder="Ej: 450.0"
                                       class="w-full px-4 py-3 pr-16 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('peso') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                                    kg
                                </div>
                            </div>
                            @error('peso')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Altura (cm)
                            </label>
                            <div class="relative">
                                <input type="number" name="altura" id="altura" value="{{ old('altura') }}"
                                       step="0.1" min="10" max="300" placeholder="Ej: 135.0"
                                       class="w-full px-4 py-3 pr-16 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('altura') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                                    cm
                                </div>
                            </div>
                            @error('altura')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                            Comentarios y Observaciones
                        </label>
                        <textarea name="comentario" id="comentario" rows="3" maxlength="500" placeholder="Observaciones sobre el desarrollo del animal..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('comentario') border-red-500 @enderror">{{ old('comentario') }}</textarea>
                        <div class="flex justify-end mt-1">
                            <span id="comentarioContador" class="text-xs text-gray-400 font-medium">0 / 500 caracteres</span>
                        </div>
                        @error('comentario')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen en Vivo (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>📋</span> Resumen del Cambio
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Preview Animal -->
                        <div class="p-4 bg-cyan-50/60 border border-cyan-100 rounded-2xl space-y-2">
                            <span class="text-xs font-bold text-cyan-900 uppercase tracking-wider">Animal Seleccionado:</span>
                            <p id="previewAnimalNombre" class="text-base font-bold text-gray-900">No seleccionado</p>
                            <div class="flex items-center gap-2 text-xs font-semibold text-gray-600 mt-1">
                                <span>Etapa Actual:</span>
                                <span id="previewEtapaActual" class="text-ganaderasoft-azul font-bold">Sin etapa</span>
                            </div>
                        </div>

                        <!-- Mini Stats Preview -->
                        <div class="space-y-3 text-xs text-gray-600 border-b border-gray-100 pb-4">
                            <div class="flex justify-between">
                                <span>Nueva Etapa:</span>
                                <span id="previewNuevaEtapa" class="font-extrabold text-emerald-600">No especificada</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Peso Ingresado:</span>
                                <span id="previewPeso" class="font-bold text-gray-900">--</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Altura Ingresada:</span>
                                <span id="previewAltura" class="font-bold text-gray-900">--</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Fecha del Registro:</span>
                                <span id="previewFecha" class="font-semibold text-gray-900">{{ date('d/m/Y') }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                                💾 Guardar Cambio
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
    const etapaCambioInput   = document.getElementById('etapa_cambio');

    const pesoInput          = document.getElementById('peso');
    const alturaInput        = document.getElementById('altura');
    const fechaInput         = document.getElementById('fecha_cambio');
    const comentarioInput    = document.getElementById('comentario');
    const comentarioContador = document.getElementById('comentarioContador');

    const previewNombre      = document.getElementById('previewAnimalNombre');
    const previewEtapaActual = document.getElementById('previewEtapaActual');
    const previewNuevaEtapa  = document.getElementById('previewNuevaEtapa');
    const previewPeso        = document.getElementById('previewPeso');
    const previewAltura      = document.getElementById('previewAltura');
    const previewFecha       = document.getElementById('previewFecha');

    const endpointTemplate   = '{{ route('lactancia.animal.etapa', ['id' => '__ID__']) }}';

    function renderStage(option, fetchedStage) {
        let etapaId = '', animalEtapaId = '', etapaNombre = '';

        if (fetchedStage) {
            etapaId = fetchedStage.etapa_id || (fetchedStage.etapa && fetchedStage.etapa.id) || '';
            animalEtapaId = fetchedStage.id || fetchedStage.animal_etapa_id || '';
            etapaNombre = (fetchedStage.etapa && (fetchedStage.etapa.nombre || fetchedStage.etapa.Nombre))
                || fetchedStage.nombre || fetchedStage.Nombre || fetchedStage.descripcion || '';
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

        if (!etapaCambioInput.value && etapaNombre) {
            etapaCambioInput.value = etapaNombre;
            previewNuevaEtapa.textContent = etapaNombre;
        }
    }

    async function updateStageAndPreview() {
        const option = animalSelect.options[animalSelect.selectedIndex];
        if (!animalSelect.value || !option) {
            etapaIdInput.value = '';
            animalEtapaInput.value = '';
            etapaTexto.value = '';
            previewNombre.textContent = 'No seleccionado';
            previewEtapaActual.textContent = 'Sin etapa';
        } else {
            previewNombre.textContent = option.dataset.nombre || 'Animal seleccionado';
            renderStage(option, null);
            if (!etapaIdInput.value || !etapaTexto.value || etapaTexto.value === 'Animal sin etapa activa') {
                try {
                    const response = await fetch(endpointTemplate.replace('__ID__', animalSelect.value), { headers: { Accept: 'application/json' } });
                    const payload = await response.json();
                    renderStage(option, payload && payload.data ? payload.data.etapa_actual : null);
                } catch (error) {
                    etapaTexto.value = 'No se pudo obtener la etapa';
                }
            }
        }

        // Live values preview
        previewNuevaEtapa.textContent = etapaCambioInput.value.trim() || 'No especificada';
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
    etapaCambioInput.addEventListener('input', updateStageAndPreview);
    pesoInput.addEventListener('input', updateStageAndPreview);
    alturaInput.addEventListener('input', updateStageAndPreview);
    fechaInput.addEventListener('change', updateStageAndPreview);
    if (comentarioInput) comentarioInput.addEventListener('input', updateStageAndPreview);

    updateStageAndPreview();
});
</script>
@endsection