@extends('layouts.authenticated')

@section('title', 'Nuevo Registro de Celo')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('registro-celo.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">🌡️ Nuevo Registro de Celo</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-semibold">Datos del Registro de Celo</h3>
        </div>
        <form action="{{ route('registro-celo.store') }}" method="POST" class="p-6">
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
                    <select name="celo_etapa_anid" id="celo_etapa_anid" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('celo_etapa_anid') border-red-500 @enderror">
                        <option value="">Seleccione un animal</option>
                        @foreach($animales as $animal)
                            <option value="{{ $animal['id_Animal'] }}" {{ old('celo_etapa_anid') == $animal['id_Animal'] ? 'selected' : '' }}
                                    data-etapa="{{ $animal['etapa_actual']['etapa_id'] ?? '' }}">
                                {{ $animal['Nombre'] ?? 'Animal #'.$animal['id_Animal'] }}
                                @if(isset($animal['Sexo'])) ({{ $animal['Sexo'] === 'F' ? 'Hembra' : 'Macho' }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('celo_etapa_anid')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Etapa actual <span class="text-red-500">*</span></label>
                    <input type="text" id="celo_etapa_texto" readonly
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 bg-gray-50 text-gray-600"
                           placeholder="Se completará al seleccionar el animal">
                    <input type="hidden" name="celo_etapa_etid" id="celo_etapa_etid" value="{{ old('celo_etapa_etid') }}">
                    @error('celo_etapa_etid')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Celo <span class="text-red-500">*</span></label>
                    <input type="date" name="celo_fecha" required value="{{ old('celo_fecha', date('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('celo_fecha') border-red-500 @enderror">
                    @error('celo_fecha')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observación</label>
                    <input type="text" name="celo_observacon" value="{{ old('celo_observacon') }}" maxlength="100"
                           placeholder="Observaciones del celo..."
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('celo_observacon') border-red-500 @enderror">
                    @error('celo_observacon')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('registro-celo.index') }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancelar</a>
                <button type="submit"
                        class="px-6 py-2 bg-ganaderasoft-verde text-white rounded-lg hover:bg-ganaderasoft-verde/80 transition-colors">
                    💾 Guardar
                </button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const animalSelect = document.getElementById('celo_etapa_anid');
    const etapaInput = document.getElementById('celo_etapa_etid');
    const etapaTexto = document.getElementById('celo_etapa_texto');
    const endpointTemplate = '{{ route('lactancia.animal.etapa', ['id' => '__ID__']) }}';

    function renderStage(option, fetchedStage) {
        const etapaId = fetchedStage?.etapa_id || fetchedStage?.etan_etapa_id || option?.dataset.etapa || '';
        const etapaNombre = fetchedStage?.Nombre || fetchedStage?.nombre || fetchedStage?.descripcion || '';
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
        if (etapaInput.value) return;

        try {
            const response = await fetch(endpointTemplate.replace('__ID__', animalSelect.value), { headers: { Accept: 'application/json' } });
            const payload = await response.json();
            renderStage(option, payload?.data?.etapa_actual || null);
        } catch (error) {
            etapaTexto.value = 'No se pudo obtener la etapa actual';
        }
    }

    animalSelect.addEventListener('change', updateStage);
    updateStage();
});
</script>
@endsection
