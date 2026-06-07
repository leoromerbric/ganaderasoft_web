@extends('layouts.authenticated')

@section('title', 'Nuevo Diagnóstico')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('diagnostico.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">🏥 Nuevo Diagnóstico</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-semibold">Datos del Diagnóstico</h3>
        </div>
        <form action="{{ route('diagnostico.store') }}" method="POST" class="p-6">
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
                        <select name="fk_etapa_animal_anid" required
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('fk_etapa_animal_anid') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">Seleccione un animal</option>
                        @foreach($animales as $animal)
                            <option value="{{ $animal['id_Animal'] }}" {{ old('fk_etapa_animal_anid') == $animal['id_Animal'] ? 'selected' : '' }}>
                                {{ $animal['Nombre'] ?? 'Animal #'.$animal['id_Animal'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('fk_etapa_animal_anid')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Diagnóstico</label>
                          <input type="text" name="diagnostico_tipo" value="{{ old('diagnostico_tipo') }}" maxlength="30"
                           placeholder="Tipo de diagnóstico..."
                              class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('diagnostico_tipo') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('diagnostico_tipo')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha del Diagnóstico</label>
                          <input type="date" name="diagnostico_fecha" value="{{ old('diagnostico_fecha', date('Y-m-d')) }}"
                              class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('diagnostico_fecha') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('diagnostico_fecha')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Etapa actual <span class="text-red-500">*</span></label>
                    <input type="text" id="diagnostico_etapa_texto" readonly
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 bg-gray-50 text-gray-600"
                           placeholder="Se completará al seleccionar el animal">
                    <input type="hidden" name="fk_etapa_animal_etid" id="diagnostico_etapa_etid" value="{{ old('fk_etapa_animal_etid') }}">
                    @error('fk_etapa_animal_etid')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="diagnostico_descripcion" rows="4"
                              placeholder="Descripción del diagnóstico..."
                              class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('diagnostico_descripcion') ? 'border-red-500' : 'border-gray-300' }}">{{ old('diagnostico_descripcion') }}</textarea>
                    @error('diagnostico_descripcion')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('diagnostico.index') }}"
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
    const animalSelect = document.querySelector('select[name="fk_etapa_animal_anid"]');
    const etapaInput = document.getElementById('diagnostico_etapa_etid');
    const etapaTexto = document.getElementById('diagnostico_etapa_texto');
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
            const etapa = payload?.data?.etapa_actual || null;
            const etapaId = etapa?.etapa_id || etapa?.etan_etapa_id || '';
            const etapaNombre = etapa?.Nombre || etapa?.nombre || etapa?.descripcion || '';
            etapaInput.value = etapaId;
            etapaTexto.value = etapaId ? `${etapaNombre || 'Etapa actual'} (#${etapaId})` : 'Animal sin etapa activa';
        } catch (error) {
            etapaTexto.value = 'No se pudo obtener la etapa actual';
        }
    }

    animalSelect.addEventListener('change', updateStage);
    updateStage();
});
</script>
@endsection
