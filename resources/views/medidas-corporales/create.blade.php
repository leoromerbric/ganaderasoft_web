@extends('layouts.authenticated')

@section('title', 'Nuevas medidas corporales')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl shadow-xs">
                📏
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Nuevas medidas corporales
                </h1>
                <p class="text-gray-500 text-sm mt-1">Registra la evaluación morfométrica y dimensiones físicas del ejemplar</p>
            </div>
        </div>
        <div>
            <a href="{{ route('medidas-corporales.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    <form action="{{ route('medidas-corporales.store') }}" method="POST" id="formMedidasCorporales" novalidate>
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
                            <label for="animal_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Animal <span class="text-red-500">*</span>
                            </label>
                            <select name="animal_id" id="animal_id" required
                                    class="w-full px-4 py-3 border @error('animal_id') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                                <option value="">Seleccione un animal...</option>
                                @foreach($animales as $animal)
                                    @php
                                        $animalPk = $animal['id'] ?? null;
                                        $etapaId = data_get($animal, 'etapa_actual.etapa.id') ?? data_get($animal, 'etapa_actual.etapa_id') ?? data_get($animal, 'etapa_actual.id') ?? '';
                                        $animalEtapaId = data_get($animal, 'etapa_actual.id') ?? '';
                                        $etapaNombre = data_get($animal, 'etapa_actual.etapa.nombre') ?? data_get($animal, 'etapa_actual.nombre') ?? ($etapaId ? ('Etapa #'.$etapaId) : '');
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
                                Etapa actual identificada
                            </label>
                            <input type="text" id="medida_etapa_texto" readonly
                                   class="w-full px-4 py-3 border border-gray-200 bg-gray-50 text-gray-700 rounded-xl text-sm font-semibold focus:outline-none cursor-not-allowed"
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
                            <label for="altura_hc" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Altura a la cruz (Hc)
                            </label>
                            <div class="relative">
                                <input type="number" name="altura_hc" id="altura_hc" value="{{ old('altura_hc') }}"
                                       step="0.1" min="0" max="300" placeholder="Ej: 135.0"
                                       class="w-full px-4 py-3 pr-14 border @error('altura_hc') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all font-semibold">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 uppercase">cm</span>
                            </div>
                            @error('altura_hc')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="altura_hg" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Altura a la grupa (Hg)
                            </label>
                            <div class="relative">
                                <input type="number" name="altura_hg" id="altura_hg" value="{{ old('altura_hg') }}"
                                       step="0.1" min="0" max="300" placeholder="Ej: 138.5"
                                       class="w-full px-4 py-3 pr-14 border @error('altura_hg') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all font-semibold">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 uppercase">cm</span>
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
                            <label for="perimetro_pt" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Perímetro torácico (Pt)
                            </label>
                            <div class="relative">
                                <input type="number" name="perimetro_pt" id="perimetro_pt" value="{{ old('perimetro_pt') }}"
                                       step="0.1" min="0" max="400" placeholder="Ej: 180.0"
                                       class="w-full px-4 py-3 pr-14 border @error('perimetro_pt') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all font-semibold">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 uppercase">cm</span>
                            </div>
                            @error('perimetro_pt')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="perimetro_pca" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Perímetro de caña (Pca)
                            </label>
                            <div class="relative">
                                <input type="number" name="perimetro_pca" id="perimetro_pca" value="{{ old('perimetro_pca') }}"
                                       step="0.1" min="0" max="100" placeholder="Ej: 20.5"
                                       class="w-full px-4 py-3 pr-14 border @error('perimetro_pca') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all font-semibold">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 uppercase">cm</span>
                            </div>
                            @error('perimetro_pca')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 4: Longitudes y Anchos -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>📐</span> Longitudes y anchos
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="longitud_lc" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Longitud corporal (Lc)
                            </label>
                            <div class="relative">
                                <input type="number" name="longitud_lc" id="longitud_lc" value="{{ old('longitud_lc') }}"
                                       step="0.1" min="0" max="300" placeholder="Ej: 155.0"
                                       class="w-full px-4 py-3 pr-14 border @error('longitud_lc') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all font-semibold">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 uppercase">cm</span>
                            </div>
                            @error('longitud_lc')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="longitud_lg" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Longitud de grupa (Lg)
                            </label>
                            <div class="relative">
                                <input type="number" name="longitud_lg" id="longitud_lg" value="{{ old('longitud_lg') }}"
                                       step="0.1" min="0" max="200" placeholder="Ej: 52.0"
                                       class="w-full px-4 py-3 pr-14 border @error('longitud_lg') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all font-semibold">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 uppercase">cm</span>
                            </div>
                            @error('longitud_lg')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="anchura_ag" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Ancho de grupa (Ag)
                            </label>
                            <div class="relative">
                                <input type="number" name="anchura_ag" id="anchura_ag" value="{{ old('anchura_ag') }}"
                                       step="0.1" min="0" max="200" placeholder="Ej: 50.0"
                                       class="w-full px-4 py-3 pr-14 border @error('anchura_ag') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all font-semibold">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 uppercase">cm</span>
                            </div>
                            @error('anchura_ag')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 5: Fecha y Observaciones -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>📝</span> Datos del registro
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="fecha_medicion" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de medición <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="fecha_medicion" id="fecha_medicion" required value="{{ old('fecha_medicion', date('Y-m-d')) }}"
                                   class="w-full px-4 py-3 border @error('fecha_medicion') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('fecha_medicion')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="observaciones" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="3" maxlength="255"
                                      placeholder="Notas adicionales sobre la conformación o estado del ejemplar..."
                                      class="w-full px-4 py-3 border @error('observaciones') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">{{ old('observaciones') }}</textarea>
                            @error('observaciones')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen en Vivo (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                    <div class="bg-slate-50 border-b border-gray-100 px-6 py-4">
                        <h3 class="text-base font-bold text-ganaderasoft-negro flex items-center gap-2">
                            <span>📋</span> Resumen de mediciones
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg border border-blue-100 shrink-0">
                                🐄
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-xs text-gray-400">Animal seleccionado</p>
                                <p id="previewAnimalNombre" class="font-bold text-gray-900 text-sm truncate">No seleccionado</p>
                                <p id="previewAnimalCodigo" class="text-xs font-mono text-gray-500">-</p>
                            </div>
                        </div>

                        <div class="space-y-3 text-xs text-gray-600 border-b border-gray-100 pb-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Etapa actual:</span>
                                <span id="previewEtapa" class="font-semibold text-gray-900 truncate max-w-[150px] text-right">-</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Fecha medición:</span>
                                <span id="previewFecha" class="font-semibold text-gray-900">{{ date('d/m/Y') }}</span>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Morfometría clave</p>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div class="p-2.5 bg-emerald-50 rounded-lg border border-emerald-100">
                                    <span class="text-gray-500 block">Altura (Hc):</span>
                                    <span id="previewHc" class="font-bold text-emerald-800">-</span>
                                </div>
                                <div class="p-2.5 bg-purple-50 rounded-lg border border-purple-100">
                                    <span class="text-gray-500 block">Tórax (Pt):</span>
                                    <span id="previewPt" class="font-bold text-purple-800">-</span>
                                </div>
                                <div class="p-2.5 bg-blue-50 rounded-lg border border-blue-100">
                                    <span class="text-gray-500 block">Cuerpo (Lc):</span>
                                    <span id="previewLc" class="font-bold text-blue-800">-</span>
                                </div>
                                <div class="p-2.5 bg-cyan-50 rounded-lg border border-cyan-100">
                                    <span class="text-gray-500 block">Grupa (Ag):</span>
                                    <span id="previewAg" class="font-bold text-cyan-800">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2 cursor-pointer">
                                <span>+</span> Guardar medición
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
    const etapaTexto   = document.getElementById('medida_etapa_texto');
    const etapaInput   = document.getElementById('etapa_id');
    const animalEtapaInput = document.getElementById('animal_etapa_id');
    const fechaInput   = document.getElementById('fecha_medicion');

    const hcInput = document.getElementById('altura_hc');
    const ptInput = document.getElementById('perimetro_pt');
    const lcInput = document.getElementById('longitud_lc');
    const agInput = document.getElementById('anchura_ag');

    const previewNombre = document.getElementById('previewAnimalNombre');
    const previewCodigo = document.getElementById('previewAnimalCodigo');
    const previewEtapa  = document.getElementById('previewEtapa');
    const previewFecha  = document.getElementById('previewFecha');
    const previewHc     = document.getElementById('previewHc');
    const previewPt     = document.getElementById('previewPt');
    const previewLc     = document.getElementById('previewLc');
    const previewAg     = document.getElementById('previewAg');

    const endpointTemplate = '{{ route('lactancia.animal.etapa', ['id' => '__ID__']) }}';

    function renderStage(option, fetchedStage) {
        const etapaId = (fetchedStage && (fetchedStage.etapa_id || (fetchedStage.etapa && fetchedStage.etapa.id) || fetchedStage.id || fetchedStage.etan_etapa_id)) || (option && option.dataset.etapaId) || '';
        const etapaNombre = (fetchedStage && ((fetchedStage.etapa && fetchedStage.etapa.nombre) || fetchedStage.nombre || fetchedStage.Nombre || fetchedStage.descripcion)) || (option && option.dataset.etapaNombre) || '';
        const animalEtapaId = (fetchedStage && (fetchedStage.id || fetchedStage.animal_etapa_id)) || (option && option.dataset.animalEtapaId) || '';

        etapaInput.value = etapaId;
        animalEtapaInput.value = animalEtapaId;
        etapaTexto.value = etapaNombre || (etapaId ? 'Etapa #' + etapaId : 'Animal sin etapa activa');
        previewEtapa.textContent = etapaTexto.value;
    }

    async function updateStageAndPreview() {
        const option = animalSelect.options[animalSelect.selectedIndex];
        if (!animalSelect.value) {
            etapaInput.value = '';
            animalEtapaInput.value = '';
            etapaTexto.value = '';
            previewNombre.textContent = 'No seleccionado';
            previewCodigo.textContent = '-';
            previewEtapa.textContent  = '-';
            return;
        }

        previewNombre.textContent = option.dataset.nombre || option.textContent;
        previewCodigo.textContent = option.dataset.codigo ? '#' + option.dataset.codigo : '-';

        renderStage(option, null);
        if (etapaInput.value) return;

        try {
            const response = await fetch(endpointTemplate.replace('__ID__', animalSelect.value), { headers: { Accept: 'application/json' } });
            const payload = await response.json();
            renderStage(option, payload && payload.data ? payload.data.etapa_actual : null);
        } catch (error) {
            etapaTexto.value = 'No se pudo obtener la etapa actual';
            previewEtapa.textContent = etapaTexto.value;
        }
    }

    function updateLivePreview() {
        previewHc.textContent = hcInput && hcInput.value ? hcInput.value + ' cm' : '-';
        previewPt.textContent = ptInput && ptInput.value ? ptInput.value + ' cm' : '-';
        previewLc.textContent = lcInput && lcInput.value ? lcInput.value + ' cm' : '-';
        previewAg.textContent = agInput && agInput.value ? agInput.value + ' cm' : '-';

        if (fechaInput && fechaInput.value) {
            const parts = fechaInput.value.split('-');
            if (parts.length === 3) {
                previewFecha.textContent = `${parts[2]}/${parts[1]}/${parts[0]}`;
            }
        }
    }

    animalSelect.addEventListener('change', updateStageAndPreview);
    if (hcInput) hcInput.addEventListener('input', updateLivePreview);
    if (ptInput) ptInput.addEventListener('input', updateLivePreview);
    if (lcInput) lcInput.addEventListener('input', updateLivePreview);
    if (agInput) agInput.addEventListener('input', updateLivePreview);
    if (fechaInput) fechaInput.addEventListener('change', updateLivePreview);

    updateStageAndPreview();
    updateLivePreview();
});
</script>
@endsection