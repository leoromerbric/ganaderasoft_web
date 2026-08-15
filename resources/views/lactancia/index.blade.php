@extends('layouts.authenticated')

@section('title', 'Períodos de Lactancia')

@section('content')
@php
    $totalActivas = collect($lactancias)->filter(function($l) {
        $fechaFin = $l['fecha_fin'] ?? null;
        return is_null($fechaFin) || strtotime($fechaFin) > time();
    })->count();
    $totalFinalizadas = count($lactancias) - $totalActivas;
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                🐄 Períodos de Lactancia
            </h1>
            <p class="text-gray-500 text-sm mt-1">Gestión y seguimiento de los ciclos de lactancia de las hembras del rebaño</p>
        </div>
        <div>
            <a href="{{ route('lactancia.create') }}"
               class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center text-sm gap-1.5">
                <span class="text-base font-bold">+</span> Nuevo Período
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
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Períodos</p>
                <p id="statTotal" class="text-3xl font-extrabold text-ganaderasoft-azul">{{ count($lactancias) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-2xl">
                🐄
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Lactancias Activas</p>
                <p id="statActivas" class="text-3xl font-extrabold text-emerald-600">{{ $totalActivas }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-2xl">
                🟢
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Lactancias Finalizadas</p>
                <p id="statFinalizadas" class="text-3xl font-extrabold text-gray-600">{{ $totalFinalizadas }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-2xl">
                ⚪
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
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Animal (Hembra)</label>
                <select id="filtroAnimal" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todas las hembras</option>
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
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Estado Ciclo</label>
                <select id="filtroEstado" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todos los estados</option>
                    <option value="1">🟢 Solo Activas</option>
                    <option value="0">⚪ Solo Finalizadas</option>
                </select>
            </div>
            <div>
                <a href="{{ route('lactancia.index') }}" onclick="limpiarFiltros(event)" class="w-full px-4 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                    Limpiar Filtros
                </a>
            </div>
        </div>
    </div>

    <!-- Tabla de Lactancias -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        @if(count($lactancias) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Animal / Hembra</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha Inicio</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha Fin / Secado</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm">
                        @foreach($lactancias as $lactancia)
                        @php
                            $lactanciaId = $lactancia['id'] ?? null;
                            $animalIdReg = $lactancia['animal_id'] ?? data_get($lactancia, 'animal.id') ?? '';
                            $animalNombre = $lactancia['animal_nombre'] ?? data_get($lactancia, 'animal.nombre') ?? 'Hembra no disponible';
                            $fechaInicio = $lactancia['fecha_inicio'] ?? null;
                            $fechaFin = $lactancia['fecha_fin'] ?? null;
                            $secado = $lactancia['secado'] ?? null;

                            $isActiva = is_null($fechaFin) || strtotime($fechaFin) > time();
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition-colors fila-lactancia"
                            data-animal-id="{{ $animalIdReg }}"
                            data-activa="{{ $isActiva ? '1' : '0' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-pink-50 border border-pink-100 text-pink-700 font-bold flex items-center justify-center text-lg">
                                        🐄
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $animalNombre }}</p>
                                        <p class="text-xs text-gray-500 font-mono">ID Lactancia: #{{ $lactanciaId }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700">
                                📅 {{ $fechaInicio ? \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') : '--/--/----' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600 font-medium">
                                @if($fechaFin)
                                    <span>🏁 {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</span>
                                @else
                                    <span class="text-emerald-600 font-semibold">En curso</span>
                                @endif
                                @if($secado)
                                    <p class="text-xs text-gray-400">🍂 Secado: {{ \Carbon\Carbon::parse($secado)->format('d/m/Y') }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($isActiva)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        🟢 Activa
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700 border border-gray-200">
                                        ⚪ Finalizada
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-3">
                                    @if($lactanciaId)
                                        <a href="{{ route('lactancia.show', $lactanciaId) }}" 
                                           class="text-ganaderasoft-celeste hover:text-ganaderasoft-azul font-semibold transition-colors inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Ver
                                        </a>
                                        <a href="{{ route('lactancia.edit', $lactanciaId) }}" 
                                           class="text-amber-600 hover:text-amber-700 font-semibold transition-colors inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Editar
                                        </a>
                                        @if($isActiva)
                                            <a href="{{ route('leche.index', ['lactancia_id' => $lactanciaId]) }}" 
                                               class="text-blue-600 hover:text-blue-800 font-semibold transition-colors inline-flex items-center gap-1">
                                                🥛 Leche
                                            </a>
                                        @endif
                                        <form method="POST" action="{{ route('lactancia.destroy', $lactanciaId) }}" class="inline"
                                              onsubmit="return confirm('¿Está seguro de que desea eliminar este período de lactancia?')">
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
                    🐄
                </div>
                <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay lactancias registradas</h3>
                <p class="text-gray-500 text-sm mb-6">Comienza registrando el primer período de lactancia de una hembra</p>
                <a href="{{ route('lactancia.create') }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg text-sm">
                    + Nuevo Período
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
    const estadoSelect = document.getElementById('filtroEstado');

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
        const visibleRows = Array.from(document.querySelectorAll('.fila-lactancia')).filter(row => row.style.display !== 'none');
        const activas = visibleRows.filter(row => row.dataset.activa === '1').length;
        const finalizadas = visibleRows.length - activas;

        document.getElementById('statTotal').textContent = visibleRows.length;
        document.getElementById('statActivas').textContent = activas;
        document.getElementById('statFinalizadas').textContent = finalizadas;
    }

    function aplicarFiltros() {
        const fv = f.value;
        const rv = r.value;
        const av = a.value;
        const ev = estadoSelect.value;

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

        document.querySelectorAll('.fila-lactancia').forEach(row => {
            const animalMatch = allowedAnimals[String(row.dataset.animalId)];
            const estadoMatch = ev === '' || row.dataset.activa === ev;

            const visible = animalMatch && estadoMatch;
            row.style.display = visible ? '' : 'none';
        });

        recalcularEstadisticas();
    }

    f.addEventListener('change', aplicarFiltros);
    r.addEventListener('change', aplicarFiltros);
    a.addEventListener('change', aplicarFiltros);
    estadoSelect.addEventListener('change', aplicarFiltros);

    window.limpiarFiltros = function (e) {
        if (e && e.preventDefault) e.preventDefault();
        f.value = ''; r.value = ''; a.value = ''; estadoSelect.value = '';
        Array.from(r.options).forEach(o => { o.hidden = false; });
        opts.forEach(o => { o.hidden = false; });
        if (window.history && window.history.pushState) {
            window.history.pushState({}, '', '{{ route('lactancia.index') }}');
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