@extends('layouts.authenticated')

@section('title', 'Medidas Corporales')

@section('content')
@php
    $countRegistros = count($medidasCorporales);
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                📏 Medidas Corporales (Morfometría)
            </h1>
            <p class="text-gray-500 text-sm mt-1">Evaluación biométrica y desarrollo morfológico por animal</p>
        </div>
        <div>
            <a href="{{ route('medidas-corporales.create', ['animal_id' => $animalId]) }}"
               class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center text-sm gap-1.5">
                <span class="text-base font-bold">+</span> Nuevo Registro
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-lg">✅</span>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Summary KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Mediciones</p>
                <p id="statTotalCount" class="text-3xl font-extrabold text-ganaderasoft-azul">{{ $countRegistros }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-2xl">
                📊
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Altura Prom. (HC)</p>
                <p id="statPromAltura" class="text-3xl font-extrabold text-emerald-600">{{ $estadisticas['altura_promedio'] }} cm</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-2xl">
                📏
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Longitud Prom. (LC)</p>
                <p id="statPromLongitud" class="text-3xl font-extrabold text-cyan-600">{{ $estadisticas['largura_promedio'] }} cm</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-cyan-50 flex items-center justify-center text-2xl">
                📐
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Perímetro Prom. (PT)</p>
                <p id="statPromPerimetro" class="text-3xl font-extrabold text-purple-600">{{ $estadisticas['circunferencia_promedio'] }} cm</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-2xl">
                ⭕
            </div>
        </div>
    </div>

    <!-- Filtros Bar en Vivo -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Finca</label>
                <select id="filtroFinca" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todas las fincas</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Rebaño</label>
                <select id="filtroRebano" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todos los rebaños</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Animal</label>
                <select id="filtroAnimal" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all truncate">
                    <option value="">Todos los animales</option>
                    @foreach($animales as $animal)
                        @php
                            $anId = $animal['id'] ?? null;
                            $rId  = data_get($animal, 'rebano.id') ?? $animal['rebano_id'] ?? '';
                            $rNm  = data_get($animal, 'rebano.nombre') ?? '';
                            $fId  = data_get($animal, 'rebano.finca.id') ?? data_get($animal, 'rebano.finca_id') ?? '';
                            $fNm  = data_get($animal, 'rebano.finca.nombre') ?? '';
                        @endphp
                        @if($anId)
                            <option value="{{ $anId }}"
                                    data-rebano-id="{{ $rId }}"
                                    data-rebano-nombre="{{ $rNm }}"
                                    data-finca-id="{{ $fId }}"
                                    data-finca-nombre="{{ $fNm }}"
                                    {{ (string)$animalId === (string)$anId ? 'selected' : '' }}>
                                {{ $animal['nombre'] ?? ('Animal #'.$anId) }} ({{ $animal['codigo_animal'] ?? 'Sin código' }})
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div>
                <a href="{{ route('medidas-corporales.index') }}" onclick="limpiarFiltros(event)"
                   class="w-full px-4 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                    Limpiar Filtros
                </a>
            </div>
        </div>
    </div>

    <!-- Tabla de Medidas Corporales -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        @if(count($medidasCorporales) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Animal</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Alturas (Cruz / Grupa)</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Perímetros (Torácico / Caña)</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Longitudes (Corporal / Grupa)</th>
                            <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm">
                        @foreach($medidasCorporales as $medida)
                        @php
                            $medidaId = $medida['id'] ?? null;
                            $anIdRef  = $medida['animal_id_ref'] ?? $medida['animal_id'] ?? data_get($medida, 'animal.id') ?? '';
                            $anNombre = $medida['animal_nombre'] ?? 'Animal no disponible';
                            $anCodigo = $medida['animal_identificacion'] ?? '';
                            
                            $alturaHc    = (float)($medida['altura_hc'] ?? 0);
                            $alturaHg    = (float)($medida['altura_hg'] ?? 0);
                            $perimetroPt = (float)($medida['perimetro_pt'] ?? 0);
                            $perimetroPca= (float)($medida['perimetro_pca'] ?? 0);
                            $longitudLc  = (float)($medida['longitud_lc'] ?? 0);
                            $longitudLg  = (float)($medida['longitud_lg'] ?? 0);
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition-colors fila-medida"
                            data-animal-id="{{ $anIdRef }}"
                            data-altura-hc="{{ $alturaHc }}"
                            data-longitud-lc="{{ $longitudLc }}"
                            data-perimetro-pt="{{ $perimetroPt }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-xl bg-cyan-50 border border-cyan-100 text-cyan-700 font-bold flex items-center justify-center text-base">
                                        🐄
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $anNombre }}</p>
                                        @if($anCodigo)
                                            <p class="text-xs text-gray-400 font-mono">#{{ $anCodigo }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 inline-block">
                                        HC: {{ $alturaHc > 0 ? number_format($alturaHc, 1).' cm' : '-' }}
                                    </span>
                                    <span class="text-xs text-gray-500 block">
                                        HG: {{ $alturaHg > 0 ? number_format($alturaHg, 1).' cm' : '-' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-purple-50 text-purple-700 border border-purple-200 inline-block">
                                        PT: {{ $perimetroPt > 0 ? number_format($perimetroPt, 1).' cm' : '-' }}
                                    </span>
                                    <span class="text-xs text-gray-500 block">
                                        PCA: {{ $perimetroPca > 0 ? number_format($perimetroPca, 1).' cm' : '-' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-cyan-50 text-cyan-700 border border-cyan-200 inline-block">
                                        LC: {{ $longitudLc > 0 ? number_format($longitudLc, 1).' cm' : '-' }}
                                    </span>
                                    <span class="text-xs text-gray-500 block">
                                        LG: {{ $longitudLg > 0 ? number_format($longitudLg, 1).' cm' : '-' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-3">
                                    @if($medidaId)
                                        <a href="{{ route('medidas-corporales.show', $medidaId) }}" 
                                           class="text-ganaderasoft-celeste hover:text-ganaderasoft-azul font-semibold transition-colors inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Ver
                                        </a>
                                        <a href="{{ route('medidas-corporales.edit', $medidaId) }}" 
                                           class="text-amber-600 hover:text-amber-700 font-semibold transition-colors inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            Editar
                                        </a>
                                        <form method="POST" action="{{ route('medidas-corporales.destroy', $medidaId) }}" class="inline"
                                              onsubmit="return confirm('¿Está seguro de que desea eliminar este registro de medidas corporales?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 font-semibold transition-colors inline-flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-ganaderasoft-celeste/10 flex items-center justify-center text-4xl">
                    📏
                </div>
                <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay registros de medidas</h3>
                <p class="text-gray-500 text-sm mb-6">Comienza evaluando la morfometría física de los animales del rebaño</p>
                <a href="{{ route('medidas-corporales.create', ['animal_id' => $animalId]) }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg text-sm">
                    + Nuevo Registro
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const f = document.getElementById('filtroFinca');
    const r = document.getElementById('filtroRebano');
    const a = document.getElementById('filtroAnimal');

    if (!f || !r || !a) return;

    const opts = Array.from(a.options).filter(o => !!o.value);
    const fM = {}, rM = {};

    opts.forEach(o => {
        const fi = o.dataset.fincaId, fn = o.dataset.fincaNombre;
        const ri = o.dataset.rebanoId, rn = o.dataset.rebanoNombre;
        if (fi && !fM[fi]) fM[fi] = fn || 'Finca #' + fi;
        if (ri && !rM[ri]) rM[ri] = { n: rn || 'Rebaño #' + ri, f: fi };
    });

    Object.keys(fM).sort((x, y) => fM[x].localeCompare(fM[y])).forEach(id => {
        const o = document.createElement('option');
        o.value = id; o.textContent = fM[id];
        f.appendChild(o);
    });

    Object.keys(rM).sort((x, y) => rM[x].n.localeCompare(rM[y].n)).forEach(id => {
        const o = document.createElement('option');
        o.value = id; o.textContent = rM[id].n; o.dataset.fincaId = rM[id].f;
        r.appendChild(o);
    });

    function recalcularEstadisticas() {
        const visibleRows = Array.from(document.querySelectorAll('.fila-medida')).filter(row => row.style.display !== 'none');
        const count = visibleRows.length;

        let hcSum = 0, hcCount = 0;
        let lcSum = 0, lcCount = 0;
        let ptSum = 0, ptCount = 0;

        visibleRows.forEach(row => {
            const hc = parseFloat(row.dataset.alturaHc || 0);
            const lc = parseFloat(row.dataset.longitudLc || 0);
            const pt = parseFloat(row.dataset.perimetroPt || 0);

            if (hc > 0) { hcSum += hc; hcCount++; }
            if (lc > 0) { lcSum += lc; lcCount++; }
            if (pt > 0) { ptSum += pt; ptCount++; }
        });

        const promHc = hcCount > 0 ? (hcSum / hcCount).toFixed(1) : '0.0';
        const promLc = lcCount > 0 ? (lcSum / lcCount).toFixed(1) : '0.0';
        const promPt = ptCount > 0 ? (ptSum / ptCount).toFixed(1) : '0.0';

        document.getElementById('statTotalCount').textContent = count;
        document.getElementById('statPromAltura').textContent = promHc + ' cm';
        document.getElementById('statPromLongitud').textContent = promLc + ' cm';
        document.getElementById('statPromPerimetro').textContent = promPt + ' cm';
    }

    function aplicarFiltros() {
        const fv = f.value;
        const rv = r.value;
        const av = a.value;

        Array.from(r.options).forEach(o => {
            if (o.value) o.hidden = !!(fv && o.dataset.fincaId !== fv);
        });
        if (r.value && r.options[r.selectedIndex]?.hidden) r.value = '';

        opts.forEach(o => {
            o.hidden = !!(fv && o.dataset.fincaId !== fv) || !!(r.value && o.dataset.rebanoId !== r.value);
        });
        if (a.value && a.options[a.selectedIndex]?.hidden) a.value = '';

        const allowedAnimals = {};
        if (av) {
            allowedAnimals[String(av)] = true;
        } else {
            opts.forEach(o => {
                if (!o.hidden && o.value) allowedAnimals[String(o.value)] = true;
            });
        }

        document.querySelectorAll('.fila-medida').forEach(row => {
            const visible = allowedAnimals[String(row.dataset.animalId)];
            row.style.display = visible ? '' : 'none';
        });

        recalcularEstadisticas();
    }

    f.addEventListener('change', aplicarFiltros);
    r.addEventListener('change', aplicarFiltros);
    a.addEventListener('change', aplicarFiltros);

    window.limpiarFiltros = function (e) {
        if (e && e.preventDefault) e.preventDefault();
        f.value = ''; r.value = ''; a.value = '';
        Array.from(r.options).forEach(o => { o.hidden = false; });
        opts.forEach(o => { o.hidden = false; });
        if (window.history && window.history.pushState) {
            window.history.pushState({}, '', '{{ route('medidas-corporales.index') }}');
        }
        aplicarFiltros();
    };

    if (a.value) {
        const selectedOpt = opts.find(o => o.value === a.value);
        if (selectedOpt) {
            f.value = selectedOpt.dataset.fincaId || '';
            r.value = selectedOpt.dataset.rebanoId || '';
        }
        aplicarFiltros();
    }
});
</script>
@endsection