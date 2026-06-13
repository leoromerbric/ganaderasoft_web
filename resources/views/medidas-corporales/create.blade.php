@extends('layouts.authenticated')

@section('title', 'Nuevas Medidas Corporales')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('medidas-corporales.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">📏 Nuevas Medidas Corporales</h2>
    </div>

    <div class="rounded-xl bg-white shadow-md">
        <div class="rounded-t-xl bg-ganaderasoft-celeste px-6 py-4 text-white">
            <h3 class="text-lg font-semibold">Datos de la medición</h3>
        </div>

        <form action="{{ route('medidas-corporales.store') }}" method="POST" class="p-6">
            @csrf

            @if($errors->any())
                <div class="mb-6 rounded border-l-4 border-red-500 bg-red-50 p-4 text-red-800">
                    <ul class="ml-4 list-disc">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Animal <span class="text-red-500">*</span></label>
                        <select name="medida_etapa_anid" id="medida_etapa_anid" required
                            class="w-full rounded-lg border px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('medida_etapa_anid') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">Seleccione un animal</option>
                        @foreach($animales as $animal)
                            @php
                                $animalPk = $animal['id_Animal'] ?? null;
                                $etapaActual = data_get($animal, 'etapa_actual', []);
                                $etapaId = $etapaActual['etapa_id'] ?? $etapaActual['etan_etapa_id'] ?? '';
                                $etapaNombre = $etapaActual['Nombre'] ?? $etapaActual['nombre'] ?? $etapaActual['descripcion'] ?? ('Etapa #'.$etapaId);
                            @endphp
                            <option value="{{ $animalPk }}" {{ old('medida_etapa_anid') == $animalPk ? 'selected' : '' }}
                                    data-etapa-id="{{ $etapaId }}"
                                    data-etapa-nombre="{{ $etapaId ? $etapaNombre : '' }}">
                                {{ $animal['Nombre'] ?? ('Animal #'.$animalPk) }}
                            </option>
                        @endforeach
                    </select>
                    @error('medida_etapa_anid')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Etapa actual <span class="text-red-500">*</span></label>
                    <input type="text" id="medida_etapa_texto" readonly
                           class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-gray-600"
                           placeholder="Se completará al seleccionar el animal">
                    <input type="hidden" name="medida_etapa_etid" id="medida_etapa_etid" value="{{ old('medida_etapa_etid') }}">
                    @error('medida_etapa_etid')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Altura a la cruz</label>
                          <input type="number" step="0.01" min="0" name="Altura_HC" value="{{ old('Altura_HC') }}"
                              class="w-full rounded-lg border px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('Altura_HC') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('Altura_HC')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Altura a la grupa</label>
                          <input type="number" step="0.01" min="0" name="Altura_HG" value="{{ old('Altura_HG') }}"
                              class="w-full rounded-lg border px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('Altura_HG') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('Altura_HG')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Perímetro torácico</label>
                          <input type="number" step="0.01" min="0" name="Perimetro_PT" value="{{ old('Perimetro_PT') }}"
                              class="w-full rounded-lg border px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('Perimetro_PT') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('Perimetro_PT')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Perímetro de caña</label>
                          <input type="number" step="0.01" min="0" name="Perimetro_PCA" value="{{ old('Perimetro_PCA') }}"
                              class="w-full rounded-lg border px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('Perimetro_PCA') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('Perimetro_PCA')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Longitud corporal</label>
                          <input type="number" step="0.01" min="0" name="Longitud_LC" value="{{ old('Longitud_LC') }}"
                              class="w-full rounded-lg border px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('Longitud_LC') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('Longitud_LC')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Longitud de grupa</label>
                          <input type="number" step="0.01" min="0" name="Longitud_LG" value="{{ old('Longitud_LG') }}"
                              class="w-full rounded-lg border px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('Longitud_LG') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('Longitud_LG')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Anchura de grupa</label>
                          <input type="number" step="0.01" min="0" name="Anchura_AG" value="{{ old('Anchura_AG') }}"
                              class="w-full rounded-lg border px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('Anchura_AG') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('Anchura_AG')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4 border-t border-gray-200 pt-6">
                <a href="{{ route('medidas-corporales.index') }}" class="rounded-lg border border-gray-300 px-6 py-2 text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const animalSelect = document.getElementById('medida_etapa_anid');
    const etapaInput = document.getElementById('medida_etapa_etid');
    const etapaTexto = document.getElementById('medida_etapa_texto');
    const endpointTemplate = '{{ route('lactancia.animal.etapa', ['id' => '__ID__']) }}';

    function renderStage(option, fetchedStage) {
        const etapaId = (fetchedStage && (fetchedStage.etapa_id || fetchedStage.etan_etapa_id)) || (option && option.dataset.etapaId) || '';
        const etapaNombre = (fetchedStage && (fetchedStage.Nombre || fetchedStage.nombre || fetchedStage.descripcion)) || (option && option.dataset.etapaNombre) || '';
        etapaInput.value = etapaId;
        etapaTexto.value = etapaId ? (etapaNombre || 'Etapa actual') : 'Animal sin etapa activa';
    }

    async function updateStage() {
        const option = animalSelect.options[animalSelect.selectedIndex];
        if (!animalSelect.value) {
            etapaInput.value = '';
            etapaTexto.value = '';
            return;
        }

        renderStage(option, null);
        if (etapaInput.value) {
            return;
        }

        try {
            const response = await fetch(endpointTemplate.replace('__ID__', animalSelect.value), { headers: { Accept: 'application/json' } });
            const payload = await response.json();
            renderStage(option, payload && payload.data ? payload.data.etapa_actual : null);
        } catch (error) {
            etapaTexto.value = 'No se pudo obtener la etapa actual';
        }
    }

    animalSelect.addEventListener('change', updateStage);
    updateStage();
});
</script>
@endsection