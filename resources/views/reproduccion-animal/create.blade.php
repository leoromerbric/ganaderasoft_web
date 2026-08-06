@extends('layouts.authenticated')

@section('title', 'Nueva Reproducción Animal')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('reproduccion-animal.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">🔬 Nueva Reproducción Animal</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-semibold">Datos de la Reproducción</h3>
        </div>
        <form action="{{ route('reproduccion-animal.store') }}" method="POST" class="p-6">
            @csrf
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded mb-6">
                    <ul class="list-disc ml-4">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Animal <span class="text-red-500">*</span></label>
                    <select name="animal_id" required
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('animal_id') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">Seleccione un animal</option>
                        @foreach($animales as $animal)
                            @php
                                $aId = $animal['id'] ?? $animal['id_Animal'] ?? '';
                                $aNombre = $animal['Nombre'] ?? ('Animal #'.$aId);
                            @endphp
                            <option value="{{ $aId }}" {{ old('animal_id') == $aId ? 'selected' : '' }}>
                                {{ $aNombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('animal_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Etapa actual <span class="text-red-500">*</span></label>
                    <input type="text" id="repro_etapa_texto" readonly
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 bg-gray-50 text-gray-600"
                           placeholder="Se completará al seleccionar el animal">
                    <input type="hidden" name="etapa_id" id="repro_etapa_etid" value="{{ old('etapa_id') }}">
                    @error('etapa_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Reproducción</label>
                    <select name="tipo"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('tipo') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">Seleccione un tipo</option>
                        <option value="Natural" {{ old('tipo') == 'Natural' ? 'selected' : '' }}>Natural</option>
                        <option value="IA" {{ old('tipo') == 'IA' ? 'selected' : '' }}>IA</option>
                    </select>
                    @error('tipo')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Reproducción <span class="text-red-500">*</span></label>
                    <input type="date" name="fecha" required
                           value="{{ old('fecha', date('Y-m-d')) }}"
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('fecha') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('fecha')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observación</label>
                    <input type="text" name="observacion" value="{{ old('observacion') }}" maxlength="60"
                           placeholder="Observaciones..."
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('observacion') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('observacion')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('reproduccion-animal.index') }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancelar</a>
                <button type="submit"
                        class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const animalSelect = document.querySelector('select[name="animal_id"]');
    const etapaInput = document.getElementById('repro_etapa_etid');
    const etapaTexto = document.getElementById('repro_etapa_texto');
    const endpointTemplate = '{{ route('lactancia.animal.etapa', ['id' => '__ID__']) }}';

    async function updateStage() {
        if (!animalSelect.value) {
            etapaInput.value = '';
            etapaTexto.value = '';
            return;
        }

        try {
            const response = await fetch(endpointTemplate.replace('__ID__', animalSelect.value), { headers: { Accept: 'application/json' } });
            const payload = await response.json();
            const animal = payload?.data?.animal || payload?.data || {};
            const etapaActual = payload?.data?.etapa_actual || payload?.data?.etapaActual || animal?.etapa_actual || animal?.etapaActual || null;
            const etapaNode = etapaActual?.etapa || etapaActual;
            const etapaId = etapaNode?.id || etapaNode?.etapa_id || etapaActual?.etan_etapa_id || etapaActual?.etanEtapaId || '';
            const etapaNombre = etapaNode?.nombre || etapaNode?.etapa_nombre || etapaNode?.Nombre || etapaNode?.descripcion || etapaActual?.etapa_nombre || etapaActual?.nombre || 'Etapa actual';
            etapaInput.value = etapaId;
            etapaTexto.value = etapaId ? (etapaNombre || ('Etapa #' + etapaId)) : 'Animal sin etapa activa';
        } catch (error) {
            etapaTexto.value = 'No se pudo obtener la etapa actual';
        }
    }

    animalSelect.addEventListener('change', updateStage);
    updateStage();
});
</script>
@endsection
