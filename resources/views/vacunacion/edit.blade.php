@extends('layouts.authenticated')

@section('title', 'Editar Vacunación')

@section('content')
@php
    $selectedAnimales = collect(data_get($vacunacion, 'animales', []));
    $fechaValor = data_get($vacunacion, 'vacunacion_fecha');
    $fechaValor = $fechaValor ? \Illuminate\Support\Carbon::parse($fechaValor)->format('Y-m-d') : date('Y-m-d');
    $filtros = old('vacunacion_filtros', data_get($vacunacion, 'vacunacion_filtros', []));
@endphp

<div class="mb-8 flex items-center">
    <a href="{{ route('vacunacion.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
    </a>
    <div>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">💉 Editar Vacunación #{{ data_get($vacunacion, 'vacunacion_id') }}</h2>
    </div>
</div>

<div class="overflow-hidden rounded-xl bg-white shadow-md">
    <div class="rounded-t-xl bg-ganaderasoft-celeste px-6 py-4 text-white"><h3 class="text-lg font-semibold">Actualizar registro de vacunación</h3></div>
    <form action="{{ route('vacunacion.update', data_get($vacunacion, 'vacunacion_id')) }}" method="POST" class="space-y-6 p-6" id="vacunacionForm">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Vacuna utilizada *</label>
                <select name="vacunacion_vacuna_id" class="w-full rounded-lg border border-gray-300 px-4 py-2">
                    @foreach($vacunas as $vacuna)
                        <option value="{{ $vacuna['vacuna_id'] ?? '' }}" {{ old('vacunacion_vacuna_id', data_get($vacunacion, 'vacunacion_vacuna_id')) == ($vacuna['vacuna_id'] ?? '') ? 'selected' : '' }}>{{ $vacuna['vacuna_nombre'] ?? 'Vacuna' }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Fecha de vacunación *</label>
                <input type="date" name="vacunacion_fecha" value="{{ old('vacunacion_fecha', $fechaValor) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Costo individual de la dosis *</label>
                <input type="number" step="0.01" min="0" name="vacunacion_costo_dosis" id="vacunacion_costo_dosis" value="{{ old('vacunacion_costo_dosis', data_get($vacunacion, 'vacunacion_costo_dosis')) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2">
            </div>
            <div class="rounded-lg border border-ganaderasoft-celeste/40 bg-ganaderasoft-celeste/10 p-4">
                <p class="text-sm text-gray-600">Total estimado</p>
                <p id="monto-total-label" class="text-xl font-bold text-ganaderasoft-azul">0,00</p>
                <p id="animales-count-label" class="text-xs text-gray-500">0 animales seleccionados</p>
            </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
            <p class="mb-3 text-sm font-medium text-gray-700">Animales a vacunar</p>
            <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">Rebaño *</label>
                    <select name="vacunacion_rebano_id" id="filtro_rebano" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        @foreach($rebanos as $rebano)
                            <option value="{{ $rebano['id_Rebano'] ?? '' }}" {{ old('vacunacion_rebano_id', data_get($vacunacion, 'vacunacion_rebano_id')) == ($rebano['id_Rebano'] ?? '') ? 'selected' : '' }}>{{ $rebano['Nombre'] ?? ('Rebaño #'.($rebano['id_Rebano'] ?? '')) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">Sexo</label>
                    <select name="vacunacion_filtros[sexo]" id="filtro_sexo" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        <option value="">Todos</option>
                        <option value="M" {{ data_get($filtros, 'sexo') === 'M' ? 'selected' : '' }}>Macho</option>
                        <option value="H" {{ data_get($filtros, 'sexo') === 'H' ? 'selected' : '' }}>Hembra</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">Etapa</label>
                    <select name="vacunacion_filtros[etapa_id]" id="filtro_etapa" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        <option value="">Todas</option>
                        @foreach($etapas as $etapa)
                            <option value="{{ $etapa['etapa_id'] ?? '' }}" {{ data_get($filtros, 'etapa_id') == ($etapa['etapa_id'] ?? '') ? 'selected' : '' }}>{{ $etapa['etapa_nombre'] ?? ('Etapa #'.($etapa['etapa_id'] ?? '')) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="button" id="btn-cargar" class="w-full rounded-lg bg-ganaderasoft-azul px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-ganaderasoft-azul/90">Cargar animales</button>
                </div>
            </div>

            <div class="mt-4">
                <p class="mb-2 text-xs text-gray-500">Desmarque los animales que no desea vacunar. "Cargar animales" reemplaza la lista actual.</p>
                <div class="max-h-72 overflow-y-auto rounded-lg border border-gray-200 bg-white">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="sticky top-0 bg-gray-50">
                            <tr>
                                <th class="w-12 px-4 py-3 text-left">
                                    <input type="checkbox" id="check-all" checked class="rounded border-gray-300 text-ganaderasoft-verde focus:ring-ganaderasoft-verde" title="Marcar/Desmarcar todos">
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Animal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Código</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Sexo</th>
                            </tr>
                        </thead>
                        <tbody id="animales-lista" class="divide-y divide-gray-200 bg-white">
                            @forelse($selectedAnimales as $item)
                                @php
                                    $animalId = data_get($item, 'va_animal_id');
                                    $animalData = data_get($item, 'animal', []);
                                    $nombre = data_get($animalData, 'Nombre', 'Animal #'.$animalId);
                                    $codigo = data_get($animalData, 'codigo_animal');
                                    $sx = data_get($animalData, 'Sexo');
                                    $sxLabel = $sx === 'M' ? 'Macho' : ($sx === 'H' ? 'Hembra' : $sx);
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3"><input type="checkbox" class="animal-check rounded border-gray-300 text-ganaderasoft-verde focus:ring-ganaderasoft-verde" name="vacunacion_animal_ids[]" value="{{ $animalId }}" checked></td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $nombre }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $codigo ?: '—' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $sxLabel }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-4 py-4 text-center text-gray-400">No hay animales asociados. Use los filtros para cargar.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Observaciones</label>
            <textarea name="vacunacion_observacion" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-2">{{ old('vacunacion_observacion', data_get($vacunacion, 'vacunacion_observacion')) }}</textarea>
        </div>

        <div class="mt-8 flex justify-end space-x-4 border-t border-gray-200 pt-6">
            <a href="{{ route('vacunacion.index') }}" class="rounded-lg border border-gray-300 px-6 py-2 text-gray-700 hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">Actualizar</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const rebano = document.getElementById('filtro_rebano');
    const sexo = document.getElementById('filtro_sexo');
    const etapa = document.getElementById('filtro_etapa');
    const btnCargar = document.getElementById('btn-cargar');
    const lista = document.getElementById('animales-lista');
    const checkAll = document.getElementById('check-all');
    const costoInput = document.getElementById('vacunacion_costo_dosis');
    const montoLabel = document.getElementById('monto-total-label');
    const countLabel = document.getElementById('animales-count-label');
    const endpoint = '{{ route('vacunacion.animales-elegibles') }}';

    const sexoLabel = { M: 'Macho', H: 'Hembra' };

    function toMoney(value) {
        return new Intl.NumberFormat('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value || 0);
    }

    function checkedBoxes() {
        return Array.from(lista.querySelectorAll('input.animal-check:checked'));
    }

    function updateTotals() {
        const count = checkedBoxes().length;
        const costo = parseFloat(costoInput.value) || 0;
        countLabel.textContent = `${count} animales seleccionados`;
        montoLabel.textContent = toMoney(count * costo);
    }

    function bindBoxes() {
        lista.querySelectorAll('input.animal-check').forEach((cb) => cb.addEventListener('change', updateTotals));
    }

    async function cargar() {
        if (!rebano.value) {
            lista.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-red-500">Seleccione un rebaño.</td></tr>';
            return;
        }

        btnCargar.disabled = true;
        btnCargar.textContent = 'Cargando...';

        const params = new URLSearchParams({ rebano_id: rebano.value });
        if (sexo.value) params.append('sexo', sexo.value);
        if (etapa.value) params.append('etapa_id', etapa.value);

        try {
            const response = await fetch(`${endpoint}?${params.toString()}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const json = await response.json();

            if (!json.success) {
                lista.innerHTML = `<tr><td colspan="4" class="px-4 py-4 text-center text-red-500">${json.message || 'No se pudieron cargar los animales.'}</td></tr>`;
                updateTotals();
                return;
            }

            const animales = json.data || [];
            if (animales.length === 0) {
                lista.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-gray-400">No hay animales que coincidan con los filtros.</td></tr>';
                updateTotals();
                return;
            }

            lista.innerHTML = animales.map((a) => {
                const id = a.id_Animal;
                const nombre = a.Nombre || ('Animal #' + id);
                const codigo = a.codigo_animal || '—';
                const sx = sexoLabel[a.Sexo] || a.Sexo || '';
                return `<tr class="hover:bg-gray-50">
                    <td class="px-4 py-3"><input type="checkbox" class="animal-check rounded border-gray-300 text-ganaderasoft-verde focus:ring-ganaderasoft-verde" name="vacunacion_animal_ids[]" value="${id}" checked></td>
                    <td class="px-4 py-3 text-sm text-gray-900">${nombre}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">${codigo}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">${sx}</td>
                </tr>`;
            }).join('');

            checkAll.checked = true;
            bindBoxes();
            updateTotals();
        } catch (e) {
            lista.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-red-500">Error al cargar los animales.</td></tr>';
        } finally {
            btnCargar.disabled = false;
            btnCargar.textContent = 'Cargar animales';
        }
    }

    checkAll.addEventListener('change', function () {
        lista.querySelectorAll('input.animal-check').forEach((cb) => { cb.checked = checkAll.checked; });
        updateTotals();
    });

    btnCargar.addEventListener('click', cargar);
    costoInput.addEventListener('input', updateTotals);

    bindBoxes();
    updateTotals();
})();
</script>
@endpush
