@extends('layouts.authenticated')

@section('title', 'Nuevo Histórico de Aplicación')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('historico-aplicacion.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">📋 Nuevo Histórico de Aplicación</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-semibold">Datos de la Aplicación</h3>
        </div>

        <div class="mx-6 mt-6 rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800">
            <p class="font-semibold">Modo campaña de vacunación</p>
            <p class="mt-1">Seleccione una dosis de tipo rebaño o subgrupo, use "Previsualizar campaña" para ver cuántos animales se aplicarán y luego guarde.</p>
        </div>

        <form action="{{ route('historico-aplicacion.store') }}" method="POST" class="p-6">
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vacuna <span class="text-red-500">*</span></label>
                    <select name="ha_vacuna_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Seleccione una vacuna</option>
                        @foreach($vacunas as $vacuna)
                            <option value="{{ $vacuna['vacuna_id'] }}" {{ old('ha_vacuna_id') == $vacuna['vacuna_id'] ? 'selected' : '' }}>
                                {{ $vacuna['vacuna_nombre'] ?? 'Vacuna #'.$vacuna['vacuna_id'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('ha_vacuna_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Casa Comercial</label>
                    <select name="ha_casa_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">-- Sin casa comercial --</option>
                        @foreach($casas as $casa)
                            <option value="{{ $casa['casa_id'] }}" {{ old('ha_casa_id') == $casa['casa_id'] ? 'selected' : '' }}>
                                {{ $casa['laboratorio'] ?? '' }} - {{ $casa['marca_comercial'] ?? '' }} (#{{ $casa['casa_id'] }})
                            </option>
                        @endforeach
                    </select>
                    @error('ha_casa_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dosis</label>
                    <div class="mb-2 text-sm">
                        <a href="{{ route('dosis.create') }}" class="text-ganaderasoft-azul hover:underline">Agregar nueva dosis</a>
                    </div>
                    <select name="ha_dosis_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">-- Sin dosis --</option>
                        @foreach($dosis as $d)
                            <option value="{{ $d['dosis_id'] }}" {{ old('ha_dosis_id') == $d['dosis_id'] ? 'selected' : '' }}>
                                {{ $d['vacuna']['vacuna_nombre'] ?? '' }} - {{ $d['dosis_frecuencia'] ?? '' }} (#{{ $d['dosis_id'] }})
                            </option>
                        @endforeach
                    </select>
                    @if(count($dosis) === 0)
                        <p class="mt-2 text-sm text-gray-500">No hay dosis registradas. Use el enlace anterior para agregar una.</p>
                    @endif
                    @error('ha_dosis_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2 rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Previsualización de campaña</p>
                            <p class="text-xs text-gray-600">Calcula cuántos animales serán impactados con la dosis seleccionada.</p>
                        </div>
                        <button type="button" id="btn-preview-campana"
                                class="inline-flex items-center justify-center rounded-lg bg-ganaderasoft-azul px-4 py-2 text-sm font-medium text-white hover:bg-ganaderasoft-azul/90">
                            Previsualizar campaña
                        </button>
                    </div>
                    <div id="preview-campana-resultado" class="mt-3 hidden rounded border border-blue-200 bg-white p-3 text-sm"></div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Animal</label>
                    <select name="ha_animal_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">-- Seleccione un animal --</option>
                        @foreach($animales as $animal)
                            <option value="{{ $animal['id_Animal'] }}" {{ old('ha_animal_id') == $animal['id_Animal'] ? 'selected' : '' }}>
                                {{ $animal['Nombre'] ?? ('Animal #'.$animal['id_Animal']) }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Si selecciona dosis, este campo es opcional y la API puede expandir al grupo objetivo.</p>
                    @error('ha_animal_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inyección <span class="text-red-500">*</span></label>
                    <input type="date" name="fecha_inyeccion" required value="{{ old('fecha_inyeccion', date('Y-m-d')) }}"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                    @error('fecha_inyeccion')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observación</label>
                    <textarea name="observacion" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">{{ old('observacion') }}</textarea>
                    @error('observacion')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('historico-aplicacion.index') }}"
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
    const dosisSelect = document.querySelector('select[name="ha_dosis_id"]');
    const vacunaSelect = document.querySelector('select[name="ha_vacuna_id"]');
    const casaSelect = document.querySelector('select[name="ha_casa_id"]');
    const animalSelect = document.querySelector('select[name="ha_animal_id"]');
    const previewBtn = document.getElementById('btn-preview-campana');
    const previewBox = document.getElementById('preview-campana-resultado');

    function syncCampaignMode() {
        const isCampaign = !!dosisSelect.value;
        vacunaSelect.required = !isCampaign;
        casaSelect.required = !isCampaign;
    }

    dosisSelect.addEventListener('change', function () {
        syncCampaignMode();
        previewBox.classList.add('hidden');
        previewBox.innerHTML = '';
    });

    previewBtn.addEventListener('click', async function () {
        if (!dosisSelect.value) {
            previewBox.classList.remove('hidden');
            previewBox.className = 'mt-3 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700';
            previewBox.textContent = 'Seleccione una dosis para previsualizar la campaña.';
            return;
        }

        previewBtn.disabled = true;
        previewBtn.textContent = 'Calculando...';

        try {
            const response = await fetch('{{ route('historico-aplicacion.preview-campana') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ ha_dosis_id: parseInt(dosisSelect.value, 10) }),
            });

            const payload = await response.json();

            if (!response.ok || !payload.success) {
                throw new Error(payload.message || 'No se pudo calcular la campaña.');
            }

            const data = payload.data || {};
            const objetivo = data.objetivo_tipo || '-';
            const count = Number(data.animales_count || 0);
            const vacuna = data.vacuna || 'N/A';
            const casa = data.casa_comercial || 'N/A';

            previewBox.classList.remove('hidden');
            previewBox.className = 'mt-3 rounded border border-green-200 bg-green-50 p-3 text-sm text-green-800';
            previewBox.innerHTML =
                '<p class="font-semibold">Campaña lista para aplicar</p>' +
                '<p class="mt-1">Vacuna: <strong>' + vacuna + '</strong> | Casa: <strong>' + casa + '</strong></p>' +
                '<p class="mt-1">Objetivo: <strong>' + objetivo + '</strong> | Animales estimados: <strong>' + count + '</strong></p>' +
                '<p class="mt-1">Al guardar, se crearán registros en histórico por cada animal objetivo.</p>';
        } catch (error) {
            previewBox.classList.remove('hidden');
            previewBox.className = 'mt-3 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700';
            previewBox.textContent = error.message;
        } finally {
            previewBtn.disabled = false;
            previewBtn.textContent = 'Previsualizar campaña';
        }
    });

    syncCampaignMode();
});
</script>
@endsection
