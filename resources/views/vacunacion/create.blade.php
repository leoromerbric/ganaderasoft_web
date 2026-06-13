@extends('layouts.authenticated')

@section('title', 'Nueva Vacunación')

@section('content')
<div class="mb-8 flex items-center">
    <a href="{{ route('vacunacion.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
    </a>
    <div>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">💉 Nueva Vacunación</h2>
        <p class="mt-1 text-gray-600">Seleccione por rebaño completo, lista de animales o filtros</p>
    </div>
</div>

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="rounded-t-xl bg-ganaderasoft-celeste px-6 py-4 text-white"><h3 class="text-lg font-semibold">Registro principal de vacunación</h3></div>
    <form action="{{ route('vacunacion.store') }}" method="POST" class="space-y-6 p-6" id="vacunacionForm">
        @csrf

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Vacuna *</label>
                <select name="vacunacion_vacuna_id" id="vacunacion_vacuna_id" class="w-full rounded-lg border border-gray-300 px-4 py-2">
                    <option value="">Seleccione</option>
                    @foreach($vacunas as $vacuna)
                        <option value="{{ $vacuna['vacuna_id'] ?? '' }}" {{ old('vacunacion_vacuna_id') == ($vacuna['vacuna_id'] ?? '') ? 'selected' : '' }}>{{ $vacuna['vacuna_nombre'] ?? 'Vacuna' }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Casa comercial</label>
                <select name="vacunacion_casa_id" class="w-full rounded-lg border border-gray-300 px-4 py-2">
                    <option value="">No especificar</option>
                    @foreach($casas as $casa)
                        <option value="{{ $casa['casa_id'] ?? '' }}" {{ old('vacunacion_casa_id') == ($casa['casa_id'] ?? '') ? 'selected' : '' }}>{{ ($casa['laboratorio'] ?? 'Casa').' - '.($casa['marca_comercial'] ?? '') }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Rebaño *</label>
                <select name="vacunacion_rebano_id" id="vacunacion_rebano_id" class="w-full rounded-lg border border-gray-300 px-4 py-2">
                    <option value="">Seleccione</option>
                    @foreach($rebanos as $rebano)
                        <option value="{{ $rebano['id_Rebano'] ?? '' }}" {{ old('vacunacion_rebano_id') == ($rebano['id_Rebano'] ?? '') ? 'selected' : '' }}>{{ $rebano['Nombre'] ?? ('Rebaño #'.($rebano['id_Rebano'] ?? '')) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Modo de selección *</label>
                <select name="vacunacion_modo_seleccion" id="vacunacion_modo_seleccion" class="w-full rounded-lg border border-gray-300 px-4 py-2">
                    <option value="todos_rebano" {{ old('vacunacion_modo_seleccion', 'todos_rebano') === 'todos_rebano' ? 'selected' : '' }}>Todos los animales del rebaño</option>
                    <option value="lista_animales" {{ old('vacunacion_modo_seleccion') === 'lista_animales' ? 'selected' : '' }}>Lista manual de animales</option>
                    <option value="filtros" {{ old('vacunacion_modo_seleccion') === 'filtros' ? 'selected' : '' }}>Filtros dentro del rebaño</option>
                </select>
            </div>
        </div>

        <div id="bloque-lista" class="hidden rounded-lg border border-gray-200 bg-gray-50 p-4">
            <label class="mb-1 block text-sm font-medium text-gray-700">Animales (múltiple) *</label>
            <select name="vacunacion_animal_ids[]" id="vacunacion_animal_ids" multiple size="8" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                @foreach($animales as $animal)
                    @php
                        $animalId = $animal['id_Animal'] ?? null;
                        $animalRebano = $animal['id_Rebano'] ?? null;
                    @endphp
                    <option value="{{ $animalId }}" data-rebano="{{ $animalRebano }}" {{ in_array((string) $animalId, old('vacunacion_animal_ids', []), true) ? 'selected' : '' }}>
                        {{ $animal['Nombre'] ?? ('Animal #'.$animalId) }} - Rebaño #{{ $animalRebano }}
                    </option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-500">Solo se mostrarán habilitados los animales del rebaño seleccionado.</p>
        </div>

        <div id="bloque-filtros" class="hidden rounded-lg border border-gray-200 bg-gray-50 p-4">
            <p class="mb-3 text-sm font-medium text-gray-700">Filtros aplicados dentro del rebaño</p>
            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">Sexo</label>
                    <select name="vacunacion_filtros[sexo]" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        <option value="">Todos</option>
                        <option value="H" {{ old('vacunacion_filtros.sexo') === 'H' ? 'selected' : '' }}>Hembra</option>
                        <option value="M" {{ old('vacunacion_filtros.sexo') === 'M' ? 'selected' : '' }}>Macho</option>
                    </select>
                </div>
                <div><label class="mb-1 block text-xs font-medium text-gray-600">Nombre contiene</label><input type="text" name="vacunacion_filtros[nombre_like]" value="{{ old('vacunacion_filtros.nombre_like') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"></div>
                <div><label class="mb-1 block text-xs font-medium text-gray-600">Código contiene</label><input type="text" name="vacunacion_filtros[codigo_like]" value="{{ old('vacunacion_filtros.codigo_like') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"></div>
                <div><label class="mb-1 block text-xs font-medium text-gray-600">Etapa ID</label><input type="number" min="1" name="vacunacion_filtros[etapa_id]" value="{{ old('vacunacion_filtros.etapa_id') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"></div>
                <div><label class="mb-1 block text-xs font-medium text-gray-600">Edad mínima (días)</label><input type="number" min="0" name="vacunacion_filtros[edad_min_dias]" value="{{ old('vacunacion_filtros.edad_min_dias') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"></div>
                <div><label class="mb-1 block text-xs font-medium text-gray-600">Edad máxima (días)</label><input type="number" min="0" name="vacunacion_filtros[edad_max_dias]" value="{{ old('vacunacion_filtros.edad_max_dias') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Fecha de vacunación *</label>
                <input type="date" name="vacunacion_fecha" id="vacunacion_fecha" value="{{ old('vacunacion_fecha', date('Y-m-d')) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Costo por dosis *</label>
                <input type="number" step="0.01" min="0" name="vacunacion_costo_dosis" id="vacunacion_costo_dosis" value="{{ old('vacunacion_costo_dosis', '0.00') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2">
            </div>
            <div class="rounded-lg border border-ganaderasoft-celeste/40 bg-ganaderasoft-celeste/10 p-4">
                <p class="text-sm text-gray-600">Monto estimado</p>
                <p id="monto-total-label" class="text-xl font-bold text-ganaderasoft-azul">0,00</p>
                <p id="animales-count-label" class="text-xs text-gray-500">0 animales</p>
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Observación</label>
            <textarea name="vacunacion_observacion" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-2">{{ old('vacunacion_observacion') }}</textarea>
        </div>

        <div class="flex items-center justify-between border-t border-gray-200 pt-6">
            <button type="button" id="btn-preview" class="rounded-lg border border-ganaderasoft-celeste px-4 py-2 text-sm text-ganaderasoft-azul hover:bg-ganaderasoft-celeste/10">Previsualizar selección</button>
            <div class="space-x-2">
                <a href="{{ route('vacunacion.index') }}" class="rounded-lg border border-gray-300 px-6 py-2 text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="rounded-lg bg-ganaderasoft-verde px-6 py-2 text-white transition-colors hover:bg-ganaderasoft-verde/80">Guardar vacunación</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const mode = document.getElementById('vacunacion_modo_seleccion');
    const rebano = document.getElementById('vacunacion_rebano_id');
    const listaBlock = document.getElementById('bloque-lista');
    const filtrosBlock = document.getElementById('bloque-filtros');
    const animalesSelect = document.getElementById('vacunacion_animal_ids');
    const previewButton = document.getElementById('btn-preview');

    function updateModeBlocks() {
        const current = mode.value;
        listaBlock.classList.toggle('hidden', current !== 'lista_animales');
        filtrosBlock.classList.toggle('hidden', current !== 'filtros');
    }

    function filterAnimalsByRebano() {
        const selectedRebano = rebano.value;
        Array.from(animalesSelect.options).forEach((option) => {
            const belongs = !selectedRebano || option.dataset.rebano === selectedRebano;
            option.disabled = !belongs;
            if (!belongs) {
                option.selected = false;
            }
        });
    }

    function toMoney(value) {
        return new Intl.NumberFormat('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value || 0);
    }

    async function preview() {
        const form = document.getElementById('vacunacionForm');
        const data = new FormData(form);

        const payload = {
            vacunacion_vacuna_id: data.get('vacunacion_vacuna_id'),
            vacunacion_casa_id: data.get('vacunacion_casa_id') || null,
            vacunacion_rebano_id: data.get('vacunacion_rebano_id'),
            vacunacion_modo_seleccion: data.get('vacunacion_modo_seleccion'),
            vacunacion_fecha: data.get('vacunacion_fecha'),
            vacunacion_costo_dosis: data.get('vacunacion_costo_dosis') || 0,
            vacunacion_observacion: data.get('vacunacion_observacion') || null,
            vacunacion_animal_ids: data.getAll('vacunacion_animal_ids[]'),
            vacunacion_filtros: {
                sexo: data.get('vacunacion_filtros[sexo]') || null,
                nombre_like: data.get('vacunacion_filtros[nombre_like]') || null,
                codigo_like: data.get('vacunacion_filtros[codigo_like]') || null,
                edad_min_dias: data.get('vacunacion_filtros[edad_min_dias]') || null,
                edad_max_dias: data.get('vacunacion_filtros[edad_max_dias]') || null,
                etapa_id: data.get('vacunacion_filtros[etapa_id]') || null,
            }
        };

        const response = await fetch('{{ route('vacunacion.preview') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const json = await response.json();

        if (!json.success) {
            alert(json.message || 'No fue posible calcular la previsualización.');
            return;
        }

        const count = Number(json.data?.animales_count || 0);
        const amount = Number(json.data?.monto_total || 0);

        document.getElementById('animales-count-label').textContent = `${count} animales`;
        document.getElementById('monto-total-label').textContent = toMoney(amount);
    }

    mode.addEventListener('change', updateModeBlocks);
    rebano.addEventListener('change', filterAnimalsByRebano);
    previewButton.addEventListener('click', preview);

    updateModeBlocks();
    filterAnimalsByRebano();
})();
</script>
@endpush
