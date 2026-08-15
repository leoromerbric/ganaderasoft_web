@extends('layouts.authenticated')

@section('title', 'Registro de Producción de Leche')

@section('content')
@php
    $totalProduccion = array_sum(array_column($registrosLeche, 'pesaje_total'));
    $countRegistros  = count($registrosLeche);
    $promedioPesaje  = $countRegistros > 0 ? $totalProduccion / $countRegistros : 0;
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                🥛 Producción de Leche
            </h1>
            <p class="text-gray-500 text-sm mt-1">Control diario de pesaje y volumen lechero por hembra y período</p>
        </div>
        <div>
            <a href="{{ route('leche.create', ['lactancia_id' => $lactanciaId]) }}"
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
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Pesajes</p>
                <p id="statTotalCount" class="text-3xl font-extrabold text-ganaderasoft-azul">{{ $countRegistros }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-2xl">
                🥛
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Producción Total</p>
                <p id="statTotalVol" class="text-3xl font-extrabold text-emerald-600">{{ number_format($totalProduccion, 2) }} L</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-2xl">
                📊
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Promedio por Pesaje</p>
                <p id="statPromedioVol" class="text-3xl font-extrabold text-cyan-600">{{ number_format($promedioPesaje, 2) }} L</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-cyan-50 flex items-center justify-center text-2xl">
                ⚖️
            </div>
        </div>
    </div>

    <!-- Filtros Bar en Vivo -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
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
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Hembra / Lactancia</label>
                <select id="filtroAnimal" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all truncate">
                    <option value="">Todas las lactancias</option>
                    @foreach($lactancias as $lact)
                        @php
                            $li   = $lact['id'] ?? null;
                            $anId = $lact['animal_id'] ?? data_get($lact, 'animal.id') ?? '';
                            $anNm = data_get($lact, 'animal.nombre') ?? ('Animal #'.$anId);
                            $rId  = data_get($lact, 'animal.rebano.rebano_id') ?? data_get($lact, 'animal.rebano_id') ?? '';
                            $rNm  = data_get($lact, 'animal.rebano.nombre') ?? '';
                            $fId  = data_get($lact, 'animal.rebano.finca_id') ?? '';
                            $fNm  = data_get($lact, 'animal.rebano.finca.nombre') ?? ($fId ? ('Finca #'.$fId) : '');
                            $fi   = isset($lact['fecha_inicio']) ? \Carbon\Carbon::parse($lact['fecha_inicio'])->format('d/m/Y') : '';
                        @endphp
                        @if($li)
                            <option value="{{ $li }}"
                                    data-animal-id="{{ $anId }}"
                                    data-rebano-id="{{ $rId }}"
                                    data-rebano-nombre="{{ $rNm }}"
                                    data-finca-id="{{ $fId }}"
                                    data-finca-nombre="{{ $fNm }}"
                                    {{ (string)$lactanciaId === (string)$li ? 'selected' : '' }}>
                                {{ $anNm }} (Lactancia #{{ $li }}{{ $fi ? ' • '.$fi : '' }})
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Fecha Pesaje</label>
                <input type="date" id="filtroFecha" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>
            <div>
                <a href="{{ route('leche.index') }}" onclick="limpiarFiltros(event)" class="w-full px-4 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                    Limpiar Filtros
                </a>
            </div>
        </div>
    </div>

    <!-- Tabla de Leche -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        @if(count($registrosLeche) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha Pesaje</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Animal / Hembra</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cantidad Producida</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID Lactancia</th>
                            <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm">
                        @foreach($registrosLeche as $registro)
                        @php
                            $lecheId = $registro['id'] ?? null;
                            $lactanciaIdReg = $registro['lactancia_id'] ?? null;
                            $animalNombre = $registro['animal_nombre'] ?? data_get($registro, 'animal.nombre') ?? 'Animal no disponible';
                            $fechaPesaje = $registro['fecha_pesaje'] ?? null;
                            $pesajeTotal = (float)($registro['pesaje_total'] ?? 0);
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition-colors fila-leche"
                            data-lactancia-id="{{ $lactanciaIdReg }}"
                            data-fecha="{{ $fechaPesaje ? substr($fechaPesaje, 0, 10) : '' }}"
                            data-volumen="{{ $pesajeTotal }}">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                📅 {{ $fechaPesaje ? \Carbon\Carbon::parse($fechaPesaje)->format('d/m/Y') : '--/--/----' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-xl bg-pink-50 border border-pink-100 text-pink-700 font-bold flex items-center justify-center text-base">
                                        🐄
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $animalNombre }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3.5 py-1.5 text-xs font-extrabold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1">
                                    🥛 {{ number_format($pesajeTotal, 2) }} L
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 font-mono text-xs">
                                #{{ $lactanciaIdReg }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-3">
                                    @if($lecheId)
                                        <a href="{{ route('leche.show', $lecheId) }}" 
                                           class="text-ganaderasoft-celeste hover:text-ganaderasoft-azul font-semibold transition-colors inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Ver
                                        </a>
                                        <a href="{{ route('leche.edit', $lecheId) }}" 
                                           class="text-amber-600 hover:text-amber-700 font-semibold transition-colors inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            Editar
                                        </a>
                                        <form method="POST" action="{{ route('leche.destroy', $lecheId) }}" class="inline"
                                              onsubmit="return confirm('¿Está seguro de que desea eliminar este registro de producción de leche?')">
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
                    🥛
                </div>
                <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay registros de leche</h3>
                <p class="text-gray-500 text-sm mb-6">Comienza registrando la primera producción lechera del rebaño</p>
                <a href="{{ route('leche.create', ['lactancia_id' => $lactanciaId]) }}"
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
    const fechaInput = document.getElementById('filtroFecha');

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
        const visibleRows = Array.from(document.querySelectorAll('.fila-leche')).filter(row => row.style.display !== 'none');
        const count = visibleRows.length;
        let totalVol = 0;

        visibleRows.forEach(row => {
            totalVol += parseFloat(row.dataset.volumen || 0);
        });

        const promedio = count > 0 ? (totalVol / count) : 0;

        document.getElementById('statTotalCount').textContent = count;
        document.getElementById('statTotalVol').textContent = totalVol.toFixed(2) + ' L';
        document.getElementById('statPromedioVol').textContent = promedio.toFixed(2) + ' L';
    }

    function aplicarFiltros() {
        const fv = f.value;
        const rv = r.value;
        const av = a.value;
        const fechav = fechaInput.value;

        Array.from(r.options).forEach(o => {
            if (o.value) o.hidden = !!(fv && o.dataset.fincaId !== fv);
        });
        if (r.value && r.options[r.selectedIndex]?.hidden) r.value = '';

        opts.forEach(o => {
            o.hidden = !!(fv && o.dataset.fincaId !== fv) || !!(r.value && o.dataset.rebanoId !== r.value);
        });
        if (a.value && a.options[a.selectedIndex]?.hidden) a.value = '';

        const allowedLactancias = {};
        if (av) {
            allowedLactancias[String(av)] = true;
        } else {
            opts.forEach(o => {
                if (!o.hidden && o.value) allowedLactancias[String(o.value)] = true;
            });
        }

        document.querySelectorAll('.fila-leche').forEach(row => {
            const lactanciaMatch = allowedLactancias[String(row.dataset.lactanciaId)];
            const fechaMatch = !fechav || row.dataset.fecha === fechav;

            const visible = lactanciaMatch && fechaMatch;
            row.style.display = visible ? '' : 'none';
        });

        recalcularEstadisticas();
    }

    f.addEventListener('change', aplicarFiltros);
    r.addEventListener('change', aplicarFiltros);
    a.addEventListener('change', aplicarFiltros);
    fechaInput.addEventListener('change', aplicarFiltros);

    window.limpiarFiltros = function (e) {
        if (e && e.preventDefault) e.preventDefault();
        f.value = ''; r.value = ''; a.value = ''; fechaInput.value = '';
        Array.from(r.options).forEach(o => { o.hidden = false; });
        opts.forEach(o => { o.hidden = false; });
        if (window.history && window.history.pushState) {
            window.history.pushState({}, '', '{{ route('leche.index') }}');
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