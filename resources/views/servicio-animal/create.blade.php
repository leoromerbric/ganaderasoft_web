@extends('layouts.authenticated')

@section('title', 'Nuevo Servicio Animal')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('servicio-animal.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">🐂 Nuevo Servicio Animal</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-semibold">Datos del Servicio Animal</h3>
        </div>
        <form action="{{ route('servicio-animal.store') }}" method="POST" class="p-6">
            @csrf
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded mb-6">
                    <ul class="list-disc ml-4">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif
            @php($borderClass = fn (string $field) => $errors->has($field) ? 'border-red-500' : 'border-gray-300')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Animal <span class="text-red-500">*</span></label>
                            <select name="servicio_id_Animal" required
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $borderClass('servicio_id_Animal') }}">
                        <option value="">Seleccione un animal</option>
                        @foreach($animales as $animal)
                            <option value="{{ $animal['id_Animal'] }}" {{ old('servicio_id_Animal') == $animal['id_Animal'] ? 'selected' : '' }}>
                                {{ $animal['Nombre'] ?? 'Animal #'.$animal['id_Animal'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('servicio_id_Animal')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Semen / Toro</label>
                            <select name="servicio_semen_id"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $borderClass('servicio_semen_id') }}">
                        <option value="">-- Sin semen --</option>
                        @foreach($semenToros as $semen)
                            <option value="{{ $semen['semen_id'] }}" {{ old('servicio_semen_id') == $semen['semen_id'] ? 'selected' : '' }}>
                                {{ $semen['toro']['Nombre'] ?? ('Semen #'.$semen['semen_id']) }}
                            </option>
                        @endforeach
                    </select>
                    @error('servicio_semen_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Etapa actual del animal</label>
                    <input type="text" id="servicio_etapa_texto" readonly
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 bg-gray-50 text-gray-600"
                           placeholder="Se completará al seleccionar el animal">
                    <input type="hidden" id="servicio_etapa_etid" value="">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Técnico</label>
                            <select name="servicio_id_Tecnico"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $borderClass('servicio_id_Tecnico') }}">
                        <option value="">-- Sin técnico --</option>
                        @foreach($personal as $persona)
                            @php
                                $personalId = data_get($persona, 'id_Tecnico') ?? data_get($persona, 'id_Personal') ?? data_get($persona, 'personal.id_Tecnico') ?? data_get($persona, 'personal.id_Personal');
                                $personalNombre = trim((data_get($persona, 'Nombre') ?? data_get($persona, 'personal.Nombre') ?? '') . ' ' . (data_get($persona, 'Apellido') ?? data_get($persona, 'personal.Apellido') ?? ''));
                            @endphp
                            @continue(!$personalId)
                            <option value="{{ $personalId }}" {{ old('servicio_id_Tecnico') == $personalId ? 'selected' : '' }}>
                                {{ $personalNombre !== '' ? $personalNombre : 'Personal #'.$personalId }}
                            </option>
                        @endforeach
                    </select>
                    @error('servicio_id_Tecnico')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Registro de Celo</label>
                            <select name="servicio_celo_id"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $borderClass('servicio_celo_id') }}">
                        <option value="">-- Sin registro de celo --</option>
                        @foreach($registrosCelo as $celo)
                            <option value="{{ $celo['celo_id'] }}" {{ old('servicio_celo_id') == $celo['celo_id'] ? 'selected' : '' }}>
                                {{ $celo['animal']['Nombre'] ?? '' }} - {{ isset($celo['celo_fecha']) ? date('d/m/Y', strtotime($celo['celo_fecha'])) : '' }} (#{{ $celo['celo_id'] }})
                            </option>
                        @endforeach
                    </select>
                    @error('servicio_celo_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Servicio</label>
                          <input type="text" name="servicio_tipo" value="{{ old('servicio_tipo') }}" maxlength="11"
                           placeholder="Ej: IA, Natural..."
                               class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $borderClass('servicio_tipo') }}">
                    @error('servicio_tipo')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha del Servicio</label>
                          <input type="date" name="servicio_fecha" value="{{ old('servicio_fecha', date('Y-m-d')) }}"
                               class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $borderClass('servicio_fecha') }}">
                    @error('servicio_fecha')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observación</label>
                          <input type="text" name="servicio_observacion" value="{{ old('servicio_observacion') }}" maxlength="100"
                           placeholder="Observaciones del servicio..."
                               class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $borderClass('servicio_observacion') }}">
                    @error('servicio_observacion')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('servicio-animal.index') }}"
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
    const animalSelect = document.querySelector('select[name="servicio_id_Animal"]');
    const etapaInput = document.getElementById('servicio_etapa_etid');
    const etapaTexto = document.getElementById('servicio_etapa_texto');
    const endpointTemplate = '{{ route('lactancia.animal.etapa', ['id' => '__ID__']) }}';

    async function updateStage() {
        if (!animalSelect || !animalSelect.value) {
            etapaInput.value = '';
            etapaTexto.value = '';
            return;
        }

        try {
            const response = await fetch(endpointTemplate.replace('__ID__', animalSelect.value), { headers: { Accept: 'application/json' } });
            const payload = await response.json();
            const etapa = payload?.data?.etapa_actual || null;
            const etapaNode = etapa?.etapa || etapa;
            const etapaId = etapaNode?.etapa_id || etapa?.etan_etapa_id || '';
            const etapaNombre = etapaNode?.etapa_nombre || etapaNode?.Nombre || etapaNode?.nombre || etapaNode?.descripcion || 'Etapa actual';
            etapaInput.value = etapaId;
            etapaTexto.value = etapaId ? etapaNombre : 'Animal sin etapa activa';
        } catch (error) {
            etapaTexto.value = 'No se pudo obtener la etapa actual';
        }
    }

    animalSelect?.addEventListener('change', updateStage);
    updateStage();
});
</script>
@endsection
