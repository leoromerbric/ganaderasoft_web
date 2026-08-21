@extends('layouts.authenticated')

@section('title', 'Nuevo servicio animal')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('servicio-animal.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">🐂 Nuevo servicio animal</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-semibold">Datos del servicio animal</h3>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Semen / toro</label>
                    <select name="semen_id"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('semen_id') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">-- Sin semen --</option>
                        @foreach($semenToros as $semen)
                            @php
                                $semId = $semen['id'] ?? $semen['semen_id'] ?? '';
                                $toroNombre = data_get($semen, 'toro.Nombre') ?? $semen['descripcion'] ?? $semen['codigo'] ?? ('Semen #'.$semId);
                            @endphp
                            <option value="{{ $semId }}" {{ old('semen_id') == $semId ? 'selected' : '' }}>
                                {{ $toroNombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('semen_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
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
                    <select name="tecnico_id"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('tecnico_id') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">-- Sin técnico --</option>
                        @foreach($personal as $persona)
                            @php
                                $personalId = data_get($persona, 'id') ?? data_get($persona, 'id_Tecnico') ?? data_get($persona, 'id_Personal') ?? data_get($persona, 'personal.id') ?? data_get($persona, 'personal.id_Tecnico') ?? data_get($persona, 'personal.id_Personal');
                                $personalNombre = trim((data_get($persona, 'Nombre') ?? data_get($persona, 'personal.Nombre') ?? '') . ' ' . (data_get($persona, 'Apellido') ?? data_get($persona, 'personal.Apellido') ?? ''));
                            @endphp
                            @continue(!$personalId)
                            <option value="{{ $personalId }}" {{ old('tecnico_id') == $personalId ? 'selected' : '' }}>
                                {{ $personalNombre !== '' ? $personalNombre : 'Personal #'.$personalId }}
                            </option>
                        @endforeach
                    </select>
                    @error('tecnico_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Registro de celo</label>
                    <select name="celo_id" id="servicio_celo_id"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('celo_id') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">-- Sin registro de celo --</option>
                        @foreach($registrosCelo as $celo)
                            @php
                                $celId = $celo['id'] ?? $celo['celo_id'] ?? '';
                                $celoAnimalId = data_get($celo, 'animal.id') ?? data_get($celo, 'animal.id_Animal') ?? data_get($celo, 'animal_id') ?? data_get($celo, 'celo_etapa_anid') ?? data_get($celo, 'etapa_animal.animal_id') ?? '';
                                $celoFecha = $celo['fecha'] ?? $celo['celo_fecha'] ?? null;
                                $animalNombre = data_get($celo, 'animal.Nombre') ?? '';
                            @endphp
                            <option value="{{ $celId }}"
                                    data-animal-id="{{ $celoAnimalId }}"
                                    {{ old('celo_id') == $celId ? 'selected' : '' }}>
                                {{ $animalNombre }} - {{ $celoFecha ? date('d/m/Y', strtotime($celoFecha)) : '' }} (#{{ $celId }})
                            </option>
                        @endforeach
                    </select>
                    @error('celo_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de servicio</label>
                    <input type="text" name="tipo" value="{{ old('tipo') }}" maxlength="11"
                           placeholder="Ej: Ia, natural..."
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('tipo') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('tipo')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha del servicio</label>
                    <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}"
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('fecha') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('fecha')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observación</label>
                    <input type="text" name="observacion" value="{{ old('observacion') }}" maxlength="100"
                           placeholder="Observaciones del servicio..."
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('observacion') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('observacion')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('servicio-animal.index') }}"
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
    const celoSelect = document.getElementById('servicio_celo_id');
    const etapaInput = document.getElementById('servicio_etapa_etid');
    const etapaTexto = document.getElementById('servicio_etapa_texto');
    const endpointTemplate = '{{ route('lactancia.animal.etapa', ['id' => '__ID__']) }}';
    const baseCeloOptions = celoSelect
        ? Array.from(celoSelect.querySelectorAll('option[value]')).filter(option => option.value !== '').map(option => option.cloneNode(true))
        : [];

    function updateCeloOptions() {
        if (!celoSelect) return;

        const selectedAnimalId = animalSelect?.value || '';
        const previousValue = celoSelect.value;

        celoSelect.innerHTML = '<option value="">-- Sin registro de celo --</option>';

        if (!selectedAnimalId) {
            return;
        }

        const filteredOptions = baseCeloOptions.filter(option => (option.dataset.animalId || '') === selectedAnimalId);
        filteredOptions.forEach(option => {
            celoSelect.appendChild(option.cloneNode(true));
        });

        if (Array.from(celoSelect.options).some(option => option.value === previousValue)) {
            celoSelect.value = previousValue;
        }
    }

    async function updateStage() {
        if (!animalSelect || !animalSelect.value) {
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

    animalSelect?.addEventListener('change', function () {
        updateCeloOptions();
        updateStage();
    });
    updateCeloOptions();
    updateStage();
});
</script>
@endsection
