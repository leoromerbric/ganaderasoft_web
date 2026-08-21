@extends('layouts.authenticated')

@section('title', 'Nuevas medidas corporales')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl">
                📏
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Nuevas medidas corporales
                </h1>
                <p class="text-gray-500 text-sm mt-1">Registra la evaluación morfométrica y dimensiones físicas del animal</p>
            </div>
        </div>
        <div>
            <a href="{{ route('medidas-corporales.index') }}" 
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
    <form action="{{ route('medidas-corporales.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Selección del Animal y Etapa -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🐄</span> Selección del animal
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Animal <span class="text-red-500">*</span>
                            </label>
                            <select name="animal_id" id="animal_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('animal_id') border-red-500 @enderror">
                                <option value="">Seleccione un animal...</option>
                                @foreach($animales as $animal)
                                    @php
                                        $animalPk = $animal['id'] ?? null;
                                        $etapaActual = data_get($animal, 'etapa_actual', []);
                                        $etapaId = $etapaActual['etapa_id'] ?? data_get($etapaActual, 'etapa.id') ?? '';
                                        $animalEtapaId = $etapaActual['id'] ?? $etapaActual['animal_etapa_id'] ?? '';
                                        $etapaNombre = data_get($etapaActual, 'etapa.nombre')
                                            ?? data_get($etapaActual, 'etapa.Nombre')
                                            ?? ($etapaActual['nombre'] ?? null)
                                            ?? ($etapaActual['descripcion'] ?? null)
                                            ?? ($etapaId ? ('Etapa #'.$etapaId) : '');
                                        $isSelected = old('animal_id', $animalId) == $animalPk;
                                    @endphp
                                    @if($animalPk)
                                        <option value="{{ $animalPk }}" {{ $isSelected ? 'selected' : '' }}
                                                data-nombre="{{ $animal['nombre'] ?? ('Animal #'.$animalPk) }}"
                                                data-codigo="{{ $animal['codigo_animal'] ?? '' }}"
                                                data-etapa-id="{{ $etapaId }}"
                                                data-animal-etapa-id="{{ $animalEtapaId }}"
                                                data-etapa-nombre="{{ $etapaNombre }}">
                                            {{ $animal['nombre'] ?? ('Animal #'.$animalPk) }} ({{ $animal['codigo_animal'] ?? 'Sin código' }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('animal_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Etapa actual del animal
                            </label>
                            <input type="text" id="medida_etapa_texto" readonly
                                   class="w-full px-4 py-3 border border-gray-200 bg-gray-50 text-gray-600 rounded-xl text-sm font-semibold"
                                   placeholder="Se completará al seleccionar el animal">
                            <input type="hidden" name="etapa_id" id="etapa_id" value="{{ old('etapa_id') }}">
                            <input type="hidden" name="animal_etapa_id" id="animal_etapa_id" value="{{ old('animal_etapa_id') }}">
                        </div>
                    </div>
                </div>

                <!-- Card 2: Alturas -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>📏</span> Alturas
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Altura a la cruz (hc)
                            </label>
                            <div class="relative">
                                <input type="number" name="altura_hc" id="altura_hc" value="{{ old('altura_hc') }}"
                                       step="0.1" min="0" max="300" placeholder="Ej: 135.0"
                                       class="w-full px-4 py-3 pr-16 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('altura_hc') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                                    Cm
                                </div>
                            </div>
                            @error('altura_hc')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Altura a la grupa (hg)
                            </label>
                            <div class="relative">
                                <input type="number" name="altura_hg" id="altura_hg" value="{{ old('altura_hg') }}"
                                       step="0.1" min="0" max="300" placeholder="Ej: 138.5"
                                       class="w-full px-4 py-3 pr-16 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('altura_hg') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                                    Cm
                                </div>
                            </div>
                            @error('altura_hg')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 3: Perímetros -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>⭕</span> Perímetros
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Perímetro torácico (pt)
                            </label>
                            <div class="relative">
                                <input type="number" name="perimetro_pt" id="perimetro_pt" value="{{ old('perimetro_pt') }}"
                                       step="0.1" min="0" max="500" placeholder="Ej: 180.0"
                                       class="w-full px-4 py-3 pr-16 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('perimetro_pt') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                                    Cm
                                </div>
                            </div>
                            @error('perimetro_pt')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Perímetro de caña (pca)
                            </label>
                            <div class="relative">
                                <input type="number" name="perimetro_pca" id="perimetro_pca" value="{{ old('perimetro_pca') }}"
                                       step="0.1" min="0" max="200" placeholder="Ej: 21.5"
                                       class="w-full px-4 py-3 pr-16 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('perimetro_pca') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                                    Cm
                                </div>
                            </div>
                            @error('perimetro_pca')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 4: Longitudes y Anchura -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>📐</span> Longitudes y anchura
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Longitud corporal (lc)
                            </label>
                            <div class="relative">
                                <input type="number" name="longitud_lc" id="longitud_lc" value="{{ old('longitud_lc') }}"
                                       step="0.1" min="0" max="500" placeholder="Ej: 160.0"
                                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('longitud_lc') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                                    Cm
                                </div>
                            </div>
                            @error('longitud_lc')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Longitud de grupa (lg)
                            </label>
                            <div class="relative">
                                <input type="number" name="longitud_lg" id="longitud_lg" value="{{ old('longitud_lg') }}"
                                       step="0.1" min="0" max="200" placeholder="Ej: 52.0"
                                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('longitud_lg') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                                    Cm
                                </div>
                            </div>
                            @error('longitud_lg')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Anchura de grupa (ag)
                            </label>
                            <div class="relative">
                                <input type="number" name="anchura_ag" id="anchura_ag" value="{{ old('anchura_ag') }}"
                                       step="0.1" min="0" max="200" placeholder="Ej: 48.0"
                                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('anchura_ag') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                                    Cm
                                </div>
                            </div>
                            @error('anchura_ag')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen en Vivo (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>📋</span> Resumen de mediciones
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Preview Animal -->
                        <div class="p-4 bg-cyan-50/60 border border-cyan-100 rounded-2xl space-y-2">
                            <span class="text-xs font-bold text-cyan-900 uppercase tracking-wider">Animal seleccionado:</span>
                            <p id="previewAnimalNombre" class="text-base font-bold text-gray-900">No seleccionado</p>
                            <p id="previewEtapa" class="text-xs font-semibold text-ganaderasoft-azul">Sin etapa</p>
                        </div>

                        <!-- Mini Stats Count -->
                        <div class="space-y-3 text-xs text-gray-600 border-b border-gray-100 pb-4">
                            <div class="flex justify-between">
                                <span>Parámetros ingresados:</span>
                                <span id="previewCount" class="font-extrabold text-ganaderasoft-azul">0 / 7</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Fecha del registro:</span>
                                <span class="font-semibold text-gray-900">{{ date('d/m/Y') }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                                💾 Guardar registro
                            </button>
                            <a href="{{ route('medidas-corporales.index') }}"
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
    const animalSelect = document.getElementById('animal_id');
    const etapaIdInput = document.getElementById('etapa_id');
    const animalEtapaInput = document.getElementById('animal_etapa_id');
    const etapaTexto   = document.getElementById('medida_etapa_texto');

    const previewNombre= document.getElementById('previewAnimalNombre');
    const previewEtapa = document.getElementById('previewEtapa');
    const previewCount = document.getElementById('previewCount');

    const measureInputs = ['altura_hc', 'altura_hg', 'perimetro_pt', 'perimetro_pca', 'longitud_lc', 'longitud_lg', 'anchura_ag'];

    const endpointTemplate = '{{ route('lactancia.animal.etapa', ['id' => '__ID__']) }}';

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
        if (animalEtapaInput) animalEtapaInput.value = animalEtapaId;
        
        const displayTexto = etapaNombre || (etapaId ? ('Etapa #' + etapaId) : '');
        etapaTexto.value = displayTexto || 'Animal sin etapa activa';
        if (previewEtapa) previewEtapa.textContent = displayTexto || 'Sin etapa';
    }

    async function updateStageAndPreview() {
        const option = animalSelect.options[animalSelect.selectedIndex];
        if (!animalSelect.value || !option) {
            etapaIdInput.value = '';
            if (animalEtapaInput) animalEtapaInput.value = '';
            etapaTexto.value = '';
            previewNombre.textContent = 'No seleccionado';
            if (previewEtapa) previewEtapa.textContent = 'Sin etapa';
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

        let filled = 0;
        measureInputs.forEach(id => {
            const input = document.getElementById(id);
            if (input && parseFloat(input.value) > 0) filled++;
        });
        previewCount.textContent = `${filled} / 7`;
    }

    animalSelect.addEventListener('change', updateStageAndPreview);
    measureInputs.forEach(id => {
        const input = document.getElementById(id);
        if (input) input.addEventListener('input', updateStageAndPreview);
    });

    updateStageAndPreview();
});
</script>
@endsection