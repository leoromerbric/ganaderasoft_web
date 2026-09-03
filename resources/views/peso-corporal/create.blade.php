@extends('layouts.authenticated')

@section('title', 'Nuevo peso corporal')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl shadow-xs">
                ⚖️
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Nuevo registro de peso
                </h1>
                <p class="text-gray-500 text-sm mt-1">Registra el peso corporal y monitorea el crecimiento ponderal del ganado</p>
            </div>
        </div>
        <div>
            <a href="{{ route('peso-corporal.index') }}" 
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
    <form action="{{ route('peso-corporal.store') }}" method="POST" id="formPesoCorporal" novalidate>
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Selección del Animal -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🐄</span> Selección de animal y etapa
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
                                        $etapaNombre = data_get($animal, 'etapa_actual.etapa.nombre') ?? data_get($animal, 'etapa_actual.nombre') ?? ($etapaId ? 'Etapa #'.$etapaId : '');
                                    @endphp
                                    <option value="{{ $animalPk }}" {{ old('animal_id') == $animalPk ? 'selected' : '' }}
                                            data-nombre="{{ $animal['nombre'] ?? ('Animal #'.$animalPk) }}"
                                            data-codigo="{{ $animal['codigo_animal'] ?? '' }}"
                                            data-etapa-id="{{ $etapaId }}"
                                            data-etapa-nombre="{{ $etapaNombre }}">
                                        {{ $animal['nombre'] ?? ('Animal #'.$animalPk) }} ({{ $animal['codigo_animal'] ?? 'Sin código' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('animal_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Etapa actual identificada</label>
                            <input type="text" id="peso_etapa_texto" readonly
                                   class="w-full px-4 py-3 border border-gray-200 bg-gray-50 rounded-xl text-sm text-gray-700 font-semibold focus:outline-none cursor-not-allowed"
                                   placeholder="Se completará al seleccionar el animal">
                            <input type="hidden" name="etapa_id" id="etapa_id" value="{{ old('etapa_id') }}">
                            @error('etapa_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 2: Datos del Pesaje -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>⚖️</span> Datos del pesaje corporal
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="fecha_peso" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de pesaje <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="fecha_peso" id="fecha_peso" required value="{{ old('fecha_peso', date('Y-m-d')) }}"
                                   class="w-full px-4 py-3 border @error('fecha_peso') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('fecha_peso')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="peso" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Peso registrado (kg) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="peso" id="peso" required step="0.01" min="0.01" max="9999"
                                       value="{{ old('peso') }}" placeholder="Ej: 350.50"
                                       class="w-full px-4 py-3 border @error('peso') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all pr-12 font-bold text-gray-900">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 uppercase">Kg</span>
                            </div>
                            @error('peso')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="comentario" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Observaciones o comentarios</label>
                            <textarea name="comentario" id="comentario" rows="3" maxlength="255"
                                      placeholder="Agregue notas sobre el estado físico, nutrición o pesaje..."
                                      class="w-full px-4 py-3 border @error('comentario') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">{{ old('comentario') }}</textarea>
                            @error('comentario')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen en Vivo (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                    <div class="bg-slate-50 border-b border-gray-100 px-6 py-4">
                        <h3 class="text-base font-bold text-ganaderasoft-negro flex items-center gap-2">
                            <span>📋</span> Resumen del registro
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
                                <span class="text-gray-500">Fecha pesaje:</span>
                                <span id="previewFecha" class="font-semibold text-gray-900">{{ date('d/m/Y') }}</span>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 mb-1">Peso a registrar:</p>
                            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-center">
                                <span id="previewPeso" class="text-2xl font-extrabold text-emerald-800">0,00 kg</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2 cursor-pointer">
                                <span>+</span> Guardar pesaje
                            </button>

                            <a href="{{ route('peso-corporal.index') }}"
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
    const etapaTexto   = document.getElementById('peso_etapa_texto');
    const etapaInput   = document.getElementById('etapa_id');
    const pesoInput    = document.getElementById('peso');
    const fechaInput   = document.getElementById('fecha_peso');

    const previewNombre = document.getElementById('previewAnimalNombre');
    const previewCodigo = document.getElementById('previewAnimalCodigo');
    const previewEtapa  = document.getElementById('previewEtapa');
    const previewFecha  = document.getElementById('previewFecha');
    const previewPeso   = document.getElementById('previewPeso');

    const endpointTemplate = '{{ route('lactancia.animal.etapa', ['id' => '__ID__']) }}';

    function renderStage(option, fetchedStage) {
        const etapaId = (fetchedStage && (fetchedStage.etapa_id || (fetchedStage.etapa && fetchedStage.etapa.id) || fetchedStage.id || fetchedStage.etan_etapa_id)) || (option && option.dataset.etapaId) || '';
        const etapaNombre = (fetchedStage && ((fetchedStage.etapa && fetchedStage.etapa.nombre) || fetchedStage.nombre || fetchedStage.Nombre || fetchedStage.descripcion)) || (option && option.dataset.etapaNombre) || '';
        etapaInput.value = etapaId;
        etapaTexto.value = etapaNombre || (etapaId ? 'Etapa #' + etapaId : 'Animal sin etapa activa');
        previewEtapa.textContent = etapaTexto.value;
    }

    async function updateStageAndPreview() {
        const option = animalSelect.options[animalSelect.selectedIndex];
        if (!animalSelect.value) {
            etapaInput.value = '';
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
        const valPeso = parseFloat(pesoInput.value);
        previewPeso.textContent = !isNaN(valPeso) ? valPeso.toFixed(2).replace('.', ',') + ' kg' : '0,00 kg';

        if (fechaInput.value) {
            const parts = fechaInput.value.split('-');
            if (parts.length === 3) {
                previewFecha.textContent = `${parts[2]}/${parts[1]}/${parts[0]}`;
            }
        }
    }

    animalSelect.addEventListener('change', updateStageAndPreview);
    pesoInput.addEventListener('input', updateLivePreview);
    fechaInput.addEventListener('change', updateLivePreview);

    updateStageAndPreview();
    updateLivePreview();
});
</script>
@endsection