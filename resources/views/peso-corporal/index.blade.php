@extends('layouts.authenticated')

@section('title', 'Peso corporal')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                📊 Control de peso corporal
            </h1>
            <p class="text-gray-500 text-sm mt-1">Registro, monitoreo y seguimiento del desarrollo ponderal de los animales</p>
        </div>
        <div>
            <a href="{{ route('peso-corporal.create') }}"
               class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center text-sm gap-1.5">
                <span class="text-base font-bold">+</span> Nuevo pesaje
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
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total pesajes</p>
                <p id="statTotal" class="text-3xl font-extrabold text-ganaderasoft-azul">{{ count($pesosCorporales) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-2xl">
                ⚖️
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Peso promedio</p>
                <p id="statPromedio" class="text-3xl font-extrabold text-emerald-600">{{ $estadisticas['peso_promedio'] }} <span class="text-base font-medium text-gray-500">Kg</span></p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-2xl">
                📈
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Peso máximo</p>
                <p id="statMaximo" class="text-3xl font-extrabold text-blue-600">{{ $estadisticas['peso_maximo'] }} <span class="text-base font-medium text-gray-500">Kg</span></p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-2xl">
                🏆
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Peso mínimo</p>
                <p id="statMinimo" class="text-3xl font-extrabold text-indigo-600">{{ $estadisticas['peso_minimo'] }} <span class="text-base font-medium text-gray-500">Kg</span></p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-2xl">
                📐
            </div>
        </div>
    </div>

    <!-- Filtros Bar en Vivo -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
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
                <select id="filtroAnimal" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todos los animales</option>
                    @foreach($animales as $animal)
                        @php
                            $rId = data_get($animal, 'rebano.id') ?? data_get($animal, 'rebano_id') ?? '';
                            $rNm = data_get($animal, 'rebano.nombre') ?? ($rId ? ('Rebaño #'.$rId) : '');
                            $fId = data_get($animal, 'rebano.finca.id') ?? data_get($animal, 'rebano.finca_id') ?? '';
                            $fNm = data_get($animal, 'rebano.finca.nombre') ?? ($fId ? ('Finca #'.$fId) : '');
                        @endphp
                        <option value="{{ $animal['id'] }}"
                                data-rebano-id="{{ $rId }}"
                                data-rebano-nombre="{{ $rNm }}"
                                data-finca-id="{{ $fId }}"
                                data-finca-nombre="{{ $fNm }}"
                                {{ $animalId == $animal['id'] ? 'selected' : '' }}>
                            {{ $animal['nombre'] ?? ('Animal #'.$animal['id']) }} ({{ $animal['codigo_animal'] ?? 'Sin código' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Desde</label>
                <input type="date" id="filtroFechaInicio" value="{{ $fechaInicio }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Hasta</label>
                <input type="date" id="filtroFechaFin" value="{{ $fechaFin }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>
            <div>
                <a href="{{ route('peso-corporal.index') }}" onclick="limpiarFiltros(event)" class="w-full px-4 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                    Limpiar filtros
                </a>
            </div>
        </div>
    </div>

    <!-- Tabla de Pesos Corporales -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        @if(count($pesosCorporales) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Animal</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha pesaje</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Peso registrado</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Observaciones</th>
                            <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm">
                        @foreach($pesosCorporales as $peso)
                        @php
                            $pesoId = $peso['id'] ?? null;
                            $animalNombre = $peso['animal_nombre'] ?? ('Animal #'.($peso['animal_id'] ?? 'N/A'));
                            $animalCodigo = $peso['animal_identificacion'] ?? '';
                            $fechaPeso = $peso['fecha_peso'] ?? null;
                            $comentario = $peso['comentario'] ?? null;
                            $valorPeso = (float) ($peso['peso'] ?? 0);
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition-colors fila-peso"
                            data-animal-id="{{ $peso['animal_id'] ?? '' }}"
                            data-fecha="{{ $fechaPeso ? substr($fechaPeso, 0, 10) : '' }}"
                            data-peso="{{ $valorPeso }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-lg">
                                        🐄
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $animalNombre }}</p>
                                        @if($animalCodigo)
                                            <p class="text-xs text-gray-500 font-mono">#{{ $animalCodigo }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700">
                                📅 {{ $fechaPeso ? \Carbon\Carbon::parse($fechaPeso)->format('d/m/Y') : '--/--/----' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3.5 py-1.5 text-sm font-extrabold rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 inline-flex items-center gap-1">
                                    ⚖️ {{ number_format($valorPeso, 2, ',', '.') }} kg
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-medium">
                                {{ $comentario ?: 'Sin observaciones' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-3">
                                    @if($pesoId)
                                        <a href="{{ route('peso-corporal.edit', $pesoId) }}" 
                                           class="text-amber-600 hover:text-amber-700 font-semibold transition-colors inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Editar
                                        </a>
                                        <form method="POST" action="{{ route('peso-corporal.destroy', $pesoId) }}" class="inline"
                                              onsubmit="return confirm('¿Está seguro de que desea eliminar este registro de peso?')">
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
                    ⚖️
                </div>
                <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay registros de peso</h3>
                <p class="text-gray-500 text-sm mb-6">Comienza guardando el primer pesaje corporal de un animal</p>
                <a href="{{ route('peso-corporal.create') }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg text-sm">
                    + Nuevo pesaje
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
    const fInicio = document.getElementById('filtroFechaInicio');
    const fFin = document.getElementById('filtroFechaFin');

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
        const visibleRows = Array.from(document.querySelectorAll('.fila-peso')).filter(row => row.style.display !== 'none');
        const pesos = visibleRows.map(row => parseFloat(row.dataset.peso)).filter(p => !isNaN(p));

        document.getElementById('statTotal').textContent = visibleRows.length;
        if (pesos.length > 0) {
            const sum = pesos.reduce((acc, val) => acc + val, 0);
            const avg = sum / pesos.length;
            const max = Math.max(...pesos);
            const min = Math.min(...pesos);

            document.getElementById('statPromedio').innerHTML = avg.toFixed(2).replace('.', ',') + ' <span class="text-base font-medium text-gray-500">kg</span>';
            document.getElementById('statMaximo').innerHTML   = max.toFixed(2).replace('.', ',') + ' <span class="text-base font-medium text-gray-500">kg</span>';
            document.getElementById('statMinimo').innerHTML   = min.toFixed(2).replace('.', ',') + ' <span class="text-base font-medium text-gray-500">kg</span>';
        } else {
            document.getElementById('statPromedio').innerHTML = '0,00 <span class="text-base font-medium text-gray-500">kg</span>';
            document.getElementById('statMaximo').innerHTML   = '0,00 <span class="text-base font-medium text-gray-500">kg</span>';
            document.getElementById('statMinimo').innerHTML   = '0,00 <span class="text-base font-medium text-gray-500">kg</span>';
        }
    }

    function aplicarFiltros() {
        const fv = f.value;
        const rv = r.value;
        const av = a.value;
        const fiVal = fInicio.value;
        const ffVal = fFin.value;

        Array.from(r.options).forEach(o => {
            if (o.value) o.hidden = !!(fv && o.dataset.fincaId !== fv);
        });
        if (r.value && r.options[r.selectedIndex]?.hidden) r.value = '';

        opts.forEach(o => {
            o.hidden = !!(fv && o.dataset.fincaId !== fv) || !!(r.value && o.dataset.rebanoId !== r.value);
        });
        if (a.value && a.options[a.selectedIndex]?.hidden) a.value = '';

        const allowedAnimals = {};
        if (a.value) {
            allowedAnimals[String(a.value)] = true;
        } else {
            opts.forEach(o => {
                if (!o.hidden && o.value) allowedAnimals[String(o.value)] = true;
            });
        }

        document.querySelectorAll('.fila-peso').forEach(row => {
            const animalMatch = allowedAnimals[String(row.dataset.animalId)];
            const rowFecha    = row.dataset.fecha;
            const fechaInicioMatch = !fiVal || (rowFecha && rowFecha >= fiVal);
            const fechaFinMatch    = !ffVal || (rowFecha && rowFecha <= ffVal);

            const visible = animalMatch && fechaInicioMatch && fechaFinMatch;
            row.style.display = visible ? '' : 'none';
        });

        recalcularEstadisticas();
    }

    f.addEventListener('change', aplicarFiltros);
    r.addEventListener('change', aplicarFiltros);
    a.addEventListener('change', aplicarFiltros);
    fInicio.addEventListener('change', aplicarFiltros);
    fFin.addEventListener('change', aplicarFiltros);

    window.limpiarFiltros = function (e) {
        if (e && e.preventDefault) e.preventDefault();
        f.value = ''; r.value = ''; a.value = ''; fInicio.value = ''; fFin.value = '';
        Array.from(r.options).forEach(o => { o.hidden = false; });
        opts.forEach(o => { o.hidden = false; });
        if (window.history && window.history.pushState) {
            window.history.pushState({}, '', '{{ route('peso-corporal.index') }}');
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