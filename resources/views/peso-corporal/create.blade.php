@extends('layouts.authenticated')

@section('title', 'Nuevo Peso Corporal')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('peso-corporal.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">📊 Nuevo Peso Corporal</h2>
    </div>

    <div class="rounded-xl bg-white shadow-md">
        <div class="rounded-t-xl bg-ganaderasoft-celeste px-6 py-4 text-white">
            <h3 class="text-lg font-semibold">Datos del pesaje</h3>
        </div>
        <form action="{{ route('peso-corporal.store') }}" method="POST" class="p-6">
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
                    <select name="peso_etapa_anid" id="peso_etapa_anid" required class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('peso_etapa_anid') border-red-500 @enderror">
                        <option value="">Seleccione un animal</option>
                        @foreach($animales as $animal)
                            @php
                                $animalPk = $animal['id_Animal'] ?? null;
                                $etapaActual = data_get($animal, 'etapa_actual', []);
                                $etapaId = $etapaActual['etapa_id'] ?? $etapaActual['etan_etapa_id'] ?? '';
                                $etapaNombre = $etapaActual['Nombre'] ?? $etapaActual['nombre'] ?? $etapaActual['descripcion'] ?? ('Etapa #'.$etapaId);
                            @endphp
                            <option value="{{ $animalPk }}" {{ old('peso_etapa_anid') == $animalPk ? 'selected' : '' }} data-etapa-id="{{ $etapaId }}" data-etapa-nombre="{{ $etapaId ? $etapaNombre : '' }}">
                                {{ $animal['Nombre'] ?? ('Animal #'.$animalPk) }}
                            </option>
                        @endforeach
                    </select>
                    @error('peso_etapa_anid')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Etapa actual</label>
                    <input type="text" id="peso_etapa_texto" readonly class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-gray-600" placeholder="Se completará al seleccionar el animal">
                    <input type="hidden" name="peso_etapa_etid" id="peso_etapa_etid" value="{{ old('peso_etapa_etid') }}">
                    @error('peso_etapa_etid')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Fecha de pesaje <span class="text-red-500">*</span></label>
                    <input type="date" name="Fecha_Peso" required value="{{ old('Fecha_Peso', date('Y-m-d')) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('Fecha_Peso') border-red-500 @enderror">
                    @error('Fecha_Peso')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Peso (kg) <span class="text-red-500">*</span></label>
                    <input type="number" name="Peso" required step="0.01" min="0.01" max="9999" value="{{ old('Peso') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('Peso') border-red-500 @enderror">
                    @error('Peso')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Comentario</label>
                    <input type="text" name="Comentario" maxlength="255" value="{{ old('Comentario') }}" placeholder="Observaciones del pesaje..." class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('Comentario') border-red-500 @enderror">
                    @error('Comentario')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4 border-t border-gray-200 pt-6">
                <a href="{{ route('peso-corporal.index') }}" class="rounded-lg border border-gray-300 px-6 py-2 text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const animalSelect = document.getElementById('peso_etapa_anid');
    const etapaInput = document.getElementById('peso_etapa_etid');
    const etapaTexto = document.getElementById('peso_etapa_texto');
    const endpointTemplate = '{{ route('lactancia.animal.etapa', ['id' => '__ID__']) }}';

    function renderStage(option, fetchedStage) {
        const etapaId = fetchedStage?.etapa_id || fetchedStage?.etan_etapa_id || option?.dataset.etapaId || '';
        const etapaNombre = fetchedStage?.Nombre || fetchedStage?.nombre || fetchedStage?.descripcion || option?.dataset.etapaNombre || '';
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
            const response = await fetch(endpointTemplate.replace('__ID__', animalSelect.value), { headers: { 'Accept': 'application/json' } });
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
@endsection@extends('layouts.authenticated')

@section('title', 'Nuevo Peso Corporal')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('peso-corporal.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">📊 Nuevo Peso Corporal</h2>
    </div>

    <div class="rounded-xl bg-white shadow-md">
        <div class="rounded-t-xl bg-ganaderasoft-celeste px-6 py-4 text-white">
            <h3 class="text-lg font-semibold">Datos del pesaje</h3>
        </div>

        <form action="{{ route('peso-corporal.store') }}" method="POST" class="p-6">
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
                    <select name="peso_etapa_anid" id="peso_etapa_anid" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('peso_etapa_anid') border-red-500 @enderror">
                        <option value="">Seleccione un animal</option>
                        @foreach($animales as $animal)
                            @php
                                $animalPk = $animal['id_Animal'] ?? null;
                                $etapaActual = data_get($animal, 'etapa_actual', []);
                                $etapaId = $etapaActual['etapa_id'] ?? $etapaActual['etan_etapa_id'] ?? '';
                                $etapaNombre = $etapaActual['Nombre'] ?? $etapaActual['nombre'] ?? $etapaActual['descripcion'] ?? ('Etapa #'.$etapaId);
                            @endphp
                            <option value="{{ $animalPk }}" {{ old('peso_etapa_anid') == $animalPk ? 'selected' : '' }}
                                    data-etapa-id="{{ $etapaId }}"
                                    data-etapa-nombre="{{ $etapaId ? $etapaNombre : '' }}">
                                {{ $animal['Nombre'] ?? ('Animal #'.$animalPk) }}
                            </option>
                        @endforeach
                    </select>
                    @error('peso_etapa_anid')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Etapa actual</label>
                    <input type="text" id="peso_etapa_texto" readonly
                           class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-gray-600"
                           placeholder="Se completará al seleccionar el animal">
                    <input type="hidden" name="peso_etapa_etid" id="peso_etapa_etid" value="{{ old('peso_etapa_etid') }}">
                    @error('peso_etapa_etid')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Fecha de pesaje <span class="text-red-500">*</span></label>
                    <input type="date" name="Fecha_Peso" required value="{{ old('Fecha_Peso', date('Y-m-d')) }}"
                           class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('Fecha_Peso') border-red-500 @enderror">
                    @error('Fecha_Peso')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Peso (kg) <span class="text-red-500">*</span></label>
                    <input type="number" name="Peso" required step="0.01" min="0.01" max="9999"
                           value="{{ old('Peso') }}"
                           class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('Peso') border-red-500 @enderror">
                    @error('Peso')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Comentario</label>
                    <input type="text" name="Comentario" maxlength="255" value="{{ old('Comentario') }}"
                           class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('Comentario') border-red-500 @enderror"
                           placeholder="Observaciones del pesaje...">
                    @error('Comentario')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4 border-t border-gray-200 pt-6">
                <a href="{{ route('peso-corporal.index') }}" class="rounded-lg border border-gray-300 px-6 py-2 text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const animalSelect = document.getElementById('peso_etapa_anid');
    const etapaInput = document.getElementById('peso_etapa_etid');
    const etapaTexto = document.getElementById('peso_etapa_texto');
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