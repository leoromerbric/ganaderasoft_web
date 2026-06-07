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
                        <select name="repro_etapa_anid" required
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('repro_etapa_anid') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">Seleccione un animal</option>
                        @foreach($animales as $animal)
                            <option value="{{ $animal['id_Animal'] }}" {{ old('repro_etapa_anid') == $animal['id_Animal'] ? 'selected' : '' }}>
                                {{ $animal['Nombre'] ?? 'Animal #'.$animal['id_Animal'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('repro_etapa_anid')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Etapa actual <span class="text-red-500">*</span></label>
                    <input type="text" id="repro_etapa_texto" readonly
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 bg-gray-50 text-gray-600"
                           placeholder="Se completará al seleccionar el animal">
                    <input type="hidden" name="repro_etapa_etid" id="repro_etapa_etid" value="{{ old('repro_etapa_etid') }}">
                    @error('repro_etapa_etid')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Reproducción</label>
                        <select name="repro_tipo_reproduccion"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('repro_tipo_reproduccion') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">Seleccione un tipo</option>
                        <option value="Natural" {{ old('repro_tipo_reproduccion') == 'Natural' ? 'selected' : '' }}>Natural</option>
                        <option value="IA" {{ old('repro_tipo_reproduccion') == 'IA' ? 'selected' : '' }}>IA</option>
                    </select>
                    @error('repro_tipo_reproduccion')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Reproducción <span class="text-red-500">*</span></label>
                          <input type="date" name="repro_fecha_reproduccion" required
                              value="{{ old('repro_fecha_reproduccion', date('Y-m-d')) }}"
                              class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('repro_fecha_reproduccion') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('repro_fecha_reproduccion')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observación</label>
                          <input type="text" name="repro_observacion" value="{{ old('repro_observacion') }}" maxlength="60"
                           placeholder="Observaciones..."
                              class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('repro_observacion') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('repro_observacion')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('reproduccion-animal.index') }}"
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
    const animalSelect = document.querySelector('select[name="repro_etapa_anid"]');
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
