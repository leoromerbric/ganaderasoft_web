@extends('layouts.authenticated')

@section('title', 'Producción de leche')

@section('content')
@php
    $totalProduccion = array_sum(array_column($registrosLeche, 'pesaje_total'));
    $countRegistros  = count($registrosLeche);
    $promedioPesaje  = $countRegistros > 0 ? $totalProduccion / $countRegistros : 0;
@endphp

<div class="space-y-8">
    <!-- Header section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">Producción de leche</h1>
            <p class="text-gray-500 text-sm mt-1">Control diario de pesajes y volumen lechero por hembra y período</p>
        </div>
        <a href="{{ route('leche.create', ['lactancia_id' => $lactanciaId]) }}"
           class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center font-medium">
            + Nuevo pesaje
        </a>
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
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total pesajes</p>
                <p id="statTotalCount" class="text-3xl font-extrabold text-ganaderasoft-azul">{{ $countRegistros }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-2xl">
                🥛
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Producción total</p>
                <p id="statTotalVol" class="text-3xl font-extrabold text-emerald-600">{{ number_format($totalProduccion, 2, ',', '.') }} L</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-2xl">
                📊
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Promedio por pesaje</p>
                <p id="statPromedioVol" class="text-3xl font-extrabold text-cyan-600">{{ number_format($promedioPesaje, 2, ',', '.') }} L</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-cyan-50 flex items-center justify-center text-2xl">
                ⚖️
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Buscar hembra</label>
                <input type="text" id="filtroBuscar" placeholder="Nombre o código..."
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Finca</label>
                <select id="filtroFinca" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todas las fincas</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Rebaño</label>
                <select id="filtroRebano" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todos los rebaños</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Fecha pesaje</label>
                <input type="date" id="filtroFecha"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>
            <div>
                <a href="{{ route('leche.index') }}" onclick="limpiarFiltros(event)"
                   class="w-full px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center h-[42px]">
                    Limpiar filtros
                </a>
            </div>
        </div>
    </div>

    <!-- Tabla de Leche -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        @if(count($registrosLeche) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="tablaContenedor">
                    <thead class="bg-gray-50">
                        <tr class="flex justify-between items-center w-full">
                            <th class="w-1/5 px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ejemplar</th>
                            <th class="w-1/5 px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha pesaje</th>
                            <th class="w-1/5 px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Volumen registrado</th>
                            <th class="w-1/5 px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lactancia</th>
                            <th class="w-1/5 px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm" id="tablaLeche">
                        @foreach($registrosLeche as $registro)
                        @php
                            $lecheId = $registro['id'] ?? null;
                            $lactanciaIdReg = $registro['lactancia_id'] ?? null;
                            $animalNombre = $registro['animal_nombre'] ?? data_get($registro, 'animal.nombre') ?? 'Animal no disponible';
                            $animalCodigo = data_get($registro, 'animal.codigo_animal') ?? data_get($registro, 'lactancia.animal.codigo_animal') ?? '';
                            $fechaPesaje = $registro['fecha_pesaje'] ?? null;
                            $pesajeTotal = (float)($registro['pesaje_total'] ?? 0);
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition-colors fila-leche flex justify-between items-center w-full"
                            data-lactancia-id="{{ $lactanciaIdReg }}"
                            data-nombre="{{ strtolower($animalNombre) }}"
                            data-codigo="{{ strtolower($animalCodigo) }}"
                            data-fecha="{{ $fechaPesaje ? substr($fechaPesaje, 0, 10) : '' }}"
                            data-volumen="{{ $pesajeTotal }}">
                            <td class="w-1/5 px-6 py-4 whitespace-nowrap overflow-hidden text-ellipsis">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 shrink-0 rounded-xl bg-pink-50 border border-pink-100 text-pink-600 font-bold flex items-center justify-center text-lg">
                                        🐄
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="font-bold text-gray-900 truncate">{{ $animalNombre }}</p>
                                        <p class="text-xs text-gray-400 font-mono">
                                            {{ $animalCodigo ? '#'.$animalCodigo : 'Lactancia: #'.$lactanciaIdReg }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="w-1/5 px-6 py-4 whitespace-nowrap text-gray-700 font-medium">
                                {{ $fechaPesaje ? \Carbon\Carbon::parse($fechaPesaje)->format('d/m/Y') : '--/--/----' }}
                            </td>
                            <td class="w-1/5 px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    🥛 {{ number_format($pesajeTotal, 2, ',', '.') }} L
                                </span>
                            </td>
                            <td class="w-1/5 px-6 py-4 whitespace-nowrap text-gray-600 font-medium">
                                @if($lactanciaIdReg)
                                    <a href="{{ route('lactancia.show', $lactanciaIdReg) }}" class="inline-flex items-center gap-1 text-ganaderasoft-azul hover:underline text-xs font-mono">
                                        Ciclo #{{ $lactanciaIdReg }}
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs">Sin asignar</span>
                                @endif
                            </td>
                            <td class="w-1/5 px-6 py-4 whitespace-nowrap text-center text-sm">
                                <div class="flex justify-center space-x-2">
                                    @if($lecheId)
                                        <!-- Botón de Ver Detalles -->
                                        <a href="{{ route('leche.show', $lecheId) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-celeste/10 text-ganaderasoft-celeste hover:bg-ganaderasoft-celeste hover:text-white transition-colors"
                                           title="Ver detalle del pesaje">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        <!-- Botón de Editar -->
                                        <a href="{{ route('leche.edit', $lecheId) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-azul/10 text-ganaderasoft-azul hover:bg-ganaderasoft-azul hover:text-white transition-colors"
                                           title="Editar pesaje">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <!-- Botón de Eliminar con Modal -->
                                        <form method="POST" action="{{ route('leche.destroy', $lecheId) }}" class="inline-block" id="form-delete-leche-{{ $lecheId }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="openGenericConfirmModal({
                                                formId: 'form-delete-leche-{{ $lecheId }}',
                                                intent: 'danger',
                                                title: 'Eliminar registro de leche',
                                                message: '¿Estás seguro de que deseas eliminar este pesaje de leche? Esta acción no se puede deshacer.',
                                                confirmText: 'Sí, eliminar'
                                            })"
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors"
                                               title="Eliminar pesaje">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Mensaje de Sin Resultados Filtrados -->
                <div id="sinResultadosFiltro" class="hidden p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-gray-50 flex items-center justify-center border border-gray-100 text-2xl">
                        🔍
                    </div>
                    <h4 class="text-base font-bold text-ganaderasoft-negro mb-1">No se encontraron pesajes de leche</h4>
                    <p class="text-gray-500 text-xs mb-4">No hay registros que coincidan con los filtros aplicados.</p>
                    <button type="button" onclick="limpiarFiltros(event)"
                            class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-xs inline-flex items-center gap-1.5 cursor-pointer">
                        Limpiar filtros
                    </button>
                </div>
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-gray-50 flex items-center justify-center border border-gray-100">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay registros de leche</h3>
                <p class="text-gray-500 text-sm mb-6">Comienza registrando la primera producción lechera del rebaño.</p>
                <a href="{{ route('leche.create', ['lactancia_id' => $lactanciaId]) }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                    + Nuevo pesaje
                </a>
            </div>
        @endif
    </div>
</div>

<x-ui.confirm-modal />

<script>
document.addEventListener('DOMContentLoaded', function () {
    const txtBuscar = document.getElementById('filtroBuscar');
    const f = document.getElementById('filtroFinca');
    const r = document.getElementById('filtroRebano');
    const fechaInput = document.getElementById('filtroFecha');
    const filas = document.querySelectorAll('.fila-leche');
    const tabla = document.getElementById('tablaContenedor');
    const sinResultados = document.getElementById('sinResultadosFiltro');

    const lactanciasData = @json($lactancias ?? []);
    const fM = {}, rM = {};
    const lactanciaMeta = {};

    lactanciasData.forEach(lact => {
        const lid = String(lact.id);
        const fi = data_get(lact, 'animal.rebano.finca.id') || data_get(lact, 'animal.rebano.finca_id');
        const fn = data_get(lact, 'animal.rebano.finca.nombre');
        const ri = data_get(lact, 'animal.rebano.id') || data_get(lact, 'animal.rebano_id');
        const rn = data_get(lact, 'animal.rebano.nombre');

        lactanciaMeta[lid] = { fincaId: fi, rebanoId: ri };

        if (fi && !fM[fi]) fM[fi] = fn || 'Finca #' + fi;
        if (ri && !rM[ri]) rM[ri] = { n: rn || 'Rebaño #' + ri, f: fi };
    });

    Object.keys(fM).sort((x, y) => fM[x].localeCompare(fM[y])).forEach(id => {
        const opt = document.createElement('option');
        opt.value = id;
        opt.textContent = fM[id];
        f.appendChild(opt);
    });

    function poblarRebanos(preserveId = null) {
        const sf = f.value;
        const prv = preserveId !== null ? preserveId : r.value;
        r.innerHTML = '<option value="">Todos los rebaños</option>';

        Object.keys(rM).forEach(id => {
            if (!sf || String(rM[id].f) === String(sf)) {
                const opt = document.createElement('option');
                opt.value = id;
                opt.textContent = rM[id].n;
                r.appendChild(opt);
            }
        });

        if (prv && Array.from(r.options).some(o => String(o.value) === String(prv))) {
            r.value = String(prv);
        } else {
            r.value = '';
        }
    }

    function recalcularEstadisticas() {
        const visibleRows = Array.from(document.querySelectorAll('.fila-leche')).filter(row => row.style.display !== 'none');
        const count = visibleRows.length;
        let totalVol = 0;

        visibleRows.forEach(row => {
            totalVol += parseFloat(row.dataset.volumen || 0);
        });

        const promedio = count > 0 ? (totalVol / count) : 0;

        const statCount = document.getElementById('statTotalCount');
        const statVol = document.getElementById('statTotalVol');
        const statProm = document.getElementById('statPromedioVol');

        if (statCount) statCount.textContent = count;
        if (statVol) statVol.textContent = totalVol.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' L';
        if (statProm) statProm.textContent = promedio.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' L';
    }

    function aplicarFiltros() {
        const query = txtBuscar ? txtBuscar.value.toLowerCase().trim() : '';
        const sf = f ? f.value : '';
        const sr = r ? r.value : '';
        const fechav = fechaInput ? fechaInput.value : '';

        let totalVisibles = 0;

        filas.forEach(fila => {
            const rowLactId = String(fila.dataset.lactanciaId || '');
            const rowNombre = fila.dataset.nombre || '';
            const rowCodigo = fila.dataset.codigo || '';
            const rowFecha  = fila.dataset.fecha || '';

            const meta = lactanciaMeta[rowLactId];
            const fi = meta ? String(meta.fincaId || '') : '';
            const ri = meta ? String(meta.rebanoId || '') : '';

            let visible = true;

            if (query !== '') {
                if (!rowNombre.includes(query) && !rowCodigo.includes(query)) {
                    visible = false;
                }
            }

            if (visible && sf !== '') {
                if (fi !== sf) visible = false;
            }

            if (visible && sr !== '') {
                if (ri !== sr) visible = false;
            }

            if (visible && fechav !== '') {
                if (rowFecha !== fechav) visible = false;
            }

            if (visible) totalVisibles++;
            fila.style.display = visible ? '' : 'none';
        });

        if (sinResultados) {
            if (totalVisibles === 0 && filas.length > 0) {
                sinResultados.classList.remove('hidden');
                if (tabla) tabla.classList.add('hidden');
            } else {
                sinResultados.classList.add('hidden');
                if (tabla) tabla.classList.remove('hidden');
            }
        }

        recalcularEstadisticas();
    }

    function data_get(obj, path) {
        if (!obj || !path) return null;
        const keys = path.split('.');
        let current = obj;
        for (const key of keys) {
            if (current === null || current === undefined) return null;
            current = current[key];
        }
        return current;
    }

    if (txtBuscar) txtBuscar.addEventListener('input', aplicarFiltros);

    if (f) {
        f.addEventListener('change', () => {
            poblarRebanos();
            aplicarFiltros();
        });
    }

    if (r) {
        r.addEventListener('change', () => {
            const selRebano = r.value;
            if (selRebano && rM[selRebano] && rM[selRebano].f) {
                const fincaAsociada = String(rM[selRebano].f);
                if (f.value !== fincaAsociada) {
                    f.value = fincaAsociada;
                    poblarRebanos(selRebano);
                }
            }
            aplicarFiltros();
        });
    }

    if (fechaInput) fechaInput.addEventListener('change', aplicarFiltros);

    poblarRebanos();

    window.limpiarFiltros = function (e) {
        if (e && e.preventDefault) e.preventDefault();
        if (txtBuscar) txtBuscar.value = '';
        if (f) f.value = '';
        if (r) r.value = '';
        if (fechaInput) fechaInput.value = '';
        poblarRebanos();
        if (window.history && window.history.pushState) {
            window.history.pushState({}, '', '{{ route('leche.index') }}');
        }
        aplicarFiltros();
    };
});
</script>
@endsection