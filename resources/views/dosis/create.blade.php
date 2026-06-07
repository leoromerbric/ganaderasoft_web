@extends('layouts.authenticated')

@section('title', 'Nueva Dosis')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('dosis.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">🧪 Nueva Dosis</h2>
    </div>

    <div class="rounded-xl bg-white shadow-md">
        <div class="rounded-t-xl bg-ganaderasoft-celeste px-6 py-4 text-white"><h3 class="text-lg font-semibold">Datos de la dosis</h3></div>
        <form action="{{ route('dosis.store') }}" method="POST" class="p-6">
            @csrf
            @if($errors->any())
                <div class="mb-6 rounded border-l-4 border-red-500 bg-red-50 p-4 text-red-800"><ul class="ml-4 list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Vacuna <span class="text-red-500">*</span></label>
                    <select name="dosis_vacuna_id" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Seleccione una vacuna</option>
                        @foreach($vacunas as $vacuna)
                            <option value="{{ $vacuna['vacuna_id'] ?? '' }}" {{ old('dosis_vacuna_id') == ($vacuna['vacuna_id'] ?? '') ? 'selected' : '' }}>{{ $vacuna['vacuna_nombre'] ?? 'Vacuna' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Casa comercial <span class="text-red-500">*</span></label>
                    <select name="dosis_casa_id" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Seleccione una casa comercial</option>
                        @foreach($casas as $casa)
                            <option value="{{ $casa['casa_id'] ?? '' }}" {{ old('dosis_casa_id') == ($casa['casa_id'] ?? '') ? 'selected' : '' }}>{{ ($casa['laboratorio'] ?? 'Casa').' - '.($casa['marca_comercial'] ?? '') }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Animal <span class="text-red-500">*</span></label>
                    <select name="dosis_etapa_animal_anid" id="dosis_etapa_animal_anid" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Seleccione un animal</option>
                        @foreach($animales as $animal)
                            <option value="{{ $animal['id_Animal'] ?? '' }}">{{ $animal['Nombre'] ?? ('Animal #'.($animal['id_Animal'] ?? '')) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Etapa actual <span class="text-red-500">*</span></label>
                    <input type="text" id="dosis_etapa_texto" readonly class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-gray-600" placeholder="Se completará al seleccionar el animal">
                    <input type="hidden" name="dosis_etapa_animal_etid" id="dosis_etapa_animal_etid" value="{{ old('dosis_etapa_animal_etid') }}">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Frecuencia <span class="text-red-500">*</span></label>
                    <input type="number" min="1" name="dosis_frecuencia" value="{{ old('dosis_frecuencia', 1) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Costo</label>
                    <input type="number" step="0.01" min="0" name="dosis_costo" value="{{ old('dosis_costo') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Costo frasco</label>
                    <input type="number" step="0.01" min="0" name="dosis_costo_frasco" value="{{ old('dosis_costo_frasco') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Fecha uso inicial <span class="text-red-500">*</span></label>
                    <input type="date" name="dosis_fecha_uso_ini" value="{{ old('dosis_fecha_uso_ini', date('Y-m-d')) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Fecha uso final</label>
                    <input type="date" name="dosis_fecha_uso_fin" value="{{ old('dosis_fecha_uso_fin') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4 border-t border-gray-200 pt-6">
                <a href="{{ route('dosis.index') }}" class="rounded-lg border border-gray-300 px-6 py-2 text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="rounded-lg bg-ganaderasoft-verde px-6 py-2 text-white transition-colors hover:bg-ganaderasoft-verde/80">💾 Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const animalSelect = document.getElementById('dosis_etapa_animal_anid');
    const etapaInput = document.getElementById('dosis_etapa_animal_etid');
    const etapaTexto = document.getElementById('dosis_etapa_texto');
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