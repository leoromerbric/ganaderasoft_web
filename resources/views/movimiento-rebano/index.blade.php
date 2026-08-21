@extends('layouts.authenticated')

@section('title', 'Movimiento de rebaño')

@section('content')
<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">Movimientos de rebaño</h1>
            <p class="text-gray-500 text-sm mt-1">Gestión y registro de traslados de animales entre fincas y rebaños</p>
        </div>
        <a href="{{ route('movimiento-rebano.create') }}"
           class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center font-medium">
            + Nuevo movimiento
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
    @php
        $totalMovs = count($movimientos);
        $totalAnimalesTrasladados = array_sum(array_map(function($m) {
            return (int)($m['total_animales'] ?? (isset($m['animales']) && is_array($m['animales']) ? count($m['animales']) : ($m['animales_count'] ?? 0)));
        }, $movimientos));
        $promedioAnimales = $totalMovs > 0 ? round($totalAnimalesTrasladados / $totalMovs, 1) : 0;
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total traslados</p>
                <p id="statTotal" class="text-3xl font-extrabold text-ganaderasoft-azul">{{ $totalMovs }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-2xl">
                🔄
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Animales trasladados</p>
                <p id="statAnimales" class="text-3xl font-extrabold text-ganaderasoft-verde-oscuro">{{ $totalAnimalesTrasladados }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-ganaderasoft-verde/20 flex items-center justify-center text-2xl">
                🐄
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Promedio por traslado</p>
                <p id="statPromedio" class="text-3xl font-extrabold text-indigo-600">{{ $promedioAnimales }} <span class="text-base font-bold text-gray-500">ejemplares</span></p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-2xl">
                📊
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <!-- Finca Origen -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Finca origen</label>
                <select id="filtroFincaOrigen" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todas las fincas</option>
                    @foreach($fincas as $finca)
                        @php $fId = $finca['id'] ?? $finca['finca_id'] ?? ''; @endphp
                        <option value="{{ $fId }}" {{ (string)$fincaId === (string)$fId ? 'selected' : '' }}>
                            {{ $finca['nombre'] ?? 'Finca #'.$fId }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Rebaño Origen -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Rebaño origen</label>
                <select id="filtroRebanoOrigen" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todos los rebaños</option>
                    @foreach($rebanos as $rebano)
                        @php
                            $rId = $rebano['id'] ?? $rebano['rebano_id'] ?? '';
                            $fId = data_get($rebano, 'finca.id', $rebano['finca_id'] ?? '');
                        @endphp
                        <option value="{{ $rId }}" {{ (string)$rebanoId === (string)$rId ? 'selected' : '' }} data-finca="{{ $fId }}">
                            {{ $rebano['nombre'] ?? 'Rebaño #'.$rId }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Finca Destino -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Finca destino</label>
                <select id="filtroFincaDestino" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todas las fincas</option>
                    @foreach($fincas as $finca)
                        @php $fId = $finca['id'] ?? $finca['finca_id'] ?? ''; @endphp
                        <option value="{{ $fId }}" {{ (string)$fincaDestinoId === (string)$fId ? 'selected' : '' }}>
                            {{ $finca['nombre'] ?? 'Finca #'.$fId }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Rebaño Destino -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Rebaño destino</label>
                <select id="filtroRebanoDestino" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todos los rebaños</option>
                    @foreach($rebanos as $rebano)
                        @php
                            $rId = $rebano['id'] ?? $rebano['rebano_id'] ?? '';
                            $fId = data_get($rebano, 'finca.id', $rebano['finca_id'] ?? '');
                        @endphp
                        <option value="{{ $rId }}" {{ (string)$rebanoDestinoId === (string)$rId ? 'selected' : '' }} data-finca="{{ $fId }}">
                            {{ $rebano['nombre'] ?? 'Rebaño #'.$rId }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Botón Limpiar -->
            <div>
                <button type="button" onclick="limpiarFiltros(event)" class="w-full px-5 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Limpiar filtros
                </button>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if(count($movimientos) > 0)
            <div class="overflow-x-auto">
                <table class="w-full border-collapse" id="tablaMovimientos">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr class="flex justify-between items-center w-full">
                            <th class="w-1/5 px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha / ID</th>
                            <th class="w-1/5 px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Origen</th>
                            <th class="w-1/5 px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Destino</th>
                            <th class="w-1/5 px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Animales</th>
                            <th class="w-1/5 px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($movimientos as $movimiento)
                            @php
                                $idMov = $movimiento['id'] ?? $movimiento['id_Movimiento'] ?? 'N/A';
                                $fincaOrigId = $movimiento['finca_id'] ?? data_get($movimiento, 'finca_origen.id');
                                $rebanoOrigId = $movimiento['rebano_id'] ?? data_get($movimiento, 'rebano_origen.id');
                                $fincaDestId = $movimiento['finca_destino_id'] ?? $movimiento['finca_id_Destino'] ?? data_get($movimiento, 'finca_destino.id');
                                $rebanoDestId = $movimiento['rebano_destino_id'] ?? $movimiento['rebano_id_Destino'] ?? data_get($movimiento, 'rebano_destino_rel.id');

                                $fincaOrigNombre = data_get($movimiento, 'finca_origen.nombre') ?? ($mapaFincas[$fincaOrigId] ?? 'Finca #'.$fincaOrigId);
                                $rebanoOrigNombre = data_get($movimiento, 'rebano_origen.nombre') ?? ($mapaRebanos[$rebanoOrigId] ?? 'Rebaño #'.$rebanoOrigId);
                                $fincaDestNombre = data_get($movimiento, 'finca_destino.nombre') ?? ($mapaFincas[$fincaDestId] ?? 'Finca #'.$fincaDestId);
                                $rebanoDestNombre = data_get($movimiento, 'rebano_destino_rel.nombre') ?? ($mapaRebanos[$rebanoDestId] ?? ($movimiento['rebano_destino'] ?? 'Rebaño #'.$rebanoDestId));

                                $cantAnimales = (int) (
                                    $movimiento['total_animales']
                                    ?? (isset($movimiento['animales']) && is_array($movimiento['animales']) ? count($movimiento['animales']) : ($movimiento['animales_count'] ?? 0))
                                );
                                $fechaMov = isset($movimiento['created_at']) ? date('d/m/Y', strtotime($movimiento['created_at'])) : '--/--/----';
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition-colors fila-movimiento flex justify-between items-center w-full"
                                data-finca-origen="{{ $fincaOrigId }}"
                                data-rebano-origen="{{ $rebanoOrigId }}"
                                data-finca-destino="{{ $fincaDestId }}"
                                data-rebano-destino="{{ $rebanoDestId }}"
                                data-cant-animales="{{ $cantAnimales }}">
                                <!-- ID y Fecha -->
                                <td class="w-1/5 px-6 py-4 whitespace-nowrap overflow-hidden text-ellipsis">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 shrink-0 rounded-xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-lg border border-ganaderasoft-celeste/20">
                                            🔄
                                        </div>
                                        <div class="overflow-hidden">
                                            <p class="font-bold text-gray-900 font-mono text-sm">#{{ $idMov }}</p>
                                            <p class="text-xs text-gray-400 font-sans">{{ $fechaMov }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Origen -->
                                <td class="w-1/5 px-6 py-4 whitespace-nowrap overflow-hidden text-ellipsis">
                                    <p class="font-bold text-gray-900 text-sm truncate">{{ $rebanoOrigNombre }}</p>
                                    <p class="text-xs text-gray-400 truncate">{{ $fincaOrigNombre }}</p>
                                </td>

                                <!-- Destino -->
                                <td class="w-1/5 px-6 py-4 whitespace-nowrap overflow-hidden text-ellipsis">
                                    <p class="font-bold text-ganaderasoft-azul text-sm truncate">{{ $rebanoDestNombre }}</p>
                                    <p class="text-xs text-gray-400 truncate">{{ $fincaDestNombre }}</p>
                                </td>

                                <!-- Total Animales -->
                                <td class="w-1/5 px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                        {{ $cantAnimales }} {{ $cantAnimales === 1 ? 'animal' : 'animales' }}
                                    </span>
                                </td>

                                <!-- Acciones -->
                                <td class="w-1/5 px-6 py-4 whitespace-nowrap text-center text-sm">
                                    <div class="flex justify-center space-x-2">
                                        <!-- Ver detalle -->
                                        <a href="{{ route('movimiento-rebano.show', $idMov) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-celeste/10 text-ganaderasoft-celeste hover:bg-ganaderasoft-celeste hover:text-white transition-colors"
                                           title="Ver detalle del traslado">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        <!-- Editar -->
                                        <a href="{{ route('movimiento-rebano.edit', $idMov) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-azul/10 text-ganaderasoft-azul hover:bg-ganaderasoft-azul hover:text-white transition-colors"
                                           title="Editar movimiento">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <!-- Eliminar -->
                                        <form method="POST" action="{{ route('movimiento-rebano.destroy', $idMov) }}" class="inline-block" id="form-delete-mov-{{ $idMov }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="openGenericConfirmModal({
                                                formId: 'form-delete-mov-{{ $idMov }}',
                                                intent: 'danger',
                                                title: 'Eliminar movimiento de rebaño',
                                                message: '¿Estás seguro de que deseas eliminar este registro de movimiento (#{{ $idMov }})? Esta acción no se puede deshacer.',
                                                confirmText: 'Sí, eliminar'
                                            })"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors cursor-pointer"
                                            title="Eliminar movimiento">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Empty state when search/filter returns 0 matches -->
            <div id="sinResultadosFiltro" class="hidden p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center text-3xl">
                    🔍
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-1">No se encontraron movimientos</h3>
                <p class="text-xs text-gray-500 mb-4 max-w-sm mx-auto">No hay registros que coincidan con los filtros seleccionados.</p>
                <button type="button" onclick="limpiarFiltros(event)"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition-colors inline-flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Limpiar filtros
                </button>
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-ganaderasoft-celeste/10 flex items-center justify-center text-4xl">
                    🔄
                </div>
                <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay movimientos registrados</h3>
                <p class="text-gray-500 text-sm mb-6">Comienza registrando el primer traslado de animales entre rebaños</p>
                <a href="{{ route('movimiento-rebano.create') }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                    + Nuevo movimiento
                </a>
            </div>
        @endif
    </div>
</div>

<x-ui.confirm-modal />

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filtroFincaOrigen   = document.getElementById('filtroFincaOrigen');
    const filtroRebanoOrigen  = document.getElementById('filtroRebanoOrigen');
    const filtroFincaDestino  = document.getElementById('filtroFincaDestino');
    const filtroRebanoDestino = document.getElementById('filtroRebanoDestino');
    const sinResultadosFiltro = document.getElementById('sinResultadosFiltro');
    const tablaMovimientos    = document.getElementById('tablaMovimientos');

    function filterSelectRebanos(selectRebano, fincaId) {
        if (!selectRebano) return;
        Array.from(selectRebano.options).forEach((opt, idx) => {
            if (idx === 0) return;
            const matches = !fincaId || opt.dataset.finca === fincaId;
            opt.style.display = matches ? '' : 'none';
        });
        if (selectRebano.value && selectRebano.options[selectRebano.selectedIndex]?.style.display === 'none') {
            selectRebano.value = '';
        }
    }

    function aplicarFiltros() {
        const fOrigen  = filtroFincaOrigen ? filtroFincaOrigen.value : '';
        const rOrigen  = filtroRebanoOrigen ? filtroRebanoOrigen.value : '';
        const fDestino = filtroFincaDestino ? filtroFincaDestino.value : '';
        const rDestino = filtroRebanoDestino ? filtroRebanoDestino.value : '';

        let total = 0, totalAnimales = 0;

        document.querySelectorAll('.fila-movimiento').forEach(function (row) {
            const matchFOrigen  = !fOrigen  || row.dataset.fincaOrigen  === fOrigen;
            const matchROrigen  = !rOrigen  || row.dataset.rebanoOrigen  === rOrigen;
            const matchFDestino = !fDestino || row.dataset.fincaDestino === fDestino;
            const matchRDestino = !rDestino || row.dataset.rebanoDestino === rDestino;

            const visible = matchFOrigen && matchROrigen && matchFDestino && matchRDestino;
            row.style.display = visible ? '' : 'none';
            if (visible) {
                total++;
                const cant = parseInt(row.dataset.cantAnimales) || 0;
                totalAnimales += cant;
            }
        });

        const statTotal = document.getElementById('statTotal');
        const statAnimales = document.getElementById('statAnimales');
        const statPromedio = document.getElementById('statPromedio');
        if (statTotal) statTotal.textContent = total;
        if (statAnimales) statAnimales.textContent = totalAnimales;
        if (statPromedio) {
            const prom = total > 0 ? (totalAnimales / total).toFixed(1) : '0';
            statPromedio.innerHTML = `${prom} <span class="text-base font-bold text-gray-500">ejemplares</span>`;
        }

        if (sinResultadosFiltro && tablaMovimientos) {
            const thead = tablaMovimientos.querySelector('thead');
            if (total === 0) {
                sinResultadosFiltro.classList.remove('hidden');
                if (thead) thead.classList.add('hidden');
            } else {
                sinResultadosFiltro.classList.add('hidden');
                if (thead) thead.classList.remove('hidden');
            }
        }
    }

    // Bidirectional sync: Origen
    if (filtroFincaOrigen) {
        filtroFincaOrigen.addEventListener('change', function () {
            filterSelectRebanos(filtroRebanoOrigen, this.value);
            aplicarFiltros();
        });
    }

    if (filtroRebanoOrigen) {
        filtroRebanoOrigen.addEventListener('change', function () {
            const selectedOpt = this.options[this.selectedIndex];
            if (selectedOpt && selectedOpt.dataset.finca && filtroFincaOrigen) {
                filtroFincaOrigen.value = selectedOpt.dataset.finca;
                filterSelectRebanos(filtroRebanoOrigen, selectedOpt.dataset.finca);
            }
            aplicarFiltros();
        });
    }

    // Bidirectional sync: Destino
    if (filtroFincaDestino) {
        filtroFincaDestino.addEventListener('change', function () {
            filterSelectRebanos(filtroRebanoDestino, this.value);
            aplicarFiltros();
        });
    }

    if (filtroRebanoDestino) {
        filtroRebanoDestino.addEventListener('change', function () {
            const selectedOpt = this.options[this.selectedIndex];
            if (selectedOpt && selectedOpt.dataset.finca && filtroFincaDestino) {
                filtroFincaDestino.value = selectedOpt.dataset.finca;
                filterSelectRebanos(filtroRebanoDestino, selectedOpt.dataset.finca);
            }
            aplicarFiltros();
        });
    }

    window.limpiarFiltros = function (e) {
        if (e && e.preventDefault) e.preventDefault();
        if (filtroFincaOrigen) filtroFincaOrigen.value   = '';
        if (filtroRebanoOrigen) filtroRebanoOrigen.value = '';
        if (filtroFincaDestino) filtroFincaDestino.value = '';
        if (filtroRebanoDestino) filtroRebanoDestino.value = '';

        filterSelectRebanos(filtroRebanoOrigen, '');
        filterSelectRebanos(filtroRebanoDestino, '');

        if (window.history && window.history.pushState) {
            window.history.pushState({}, '', '{{ route('movimiento-rebano.index') }}');
        }
        aplicarFiltros();
    };

    // Initial check
    if (filtroFincaOrigen && filtroFincaOrigen.value) {
        filterSelectRebanos(filtroRebanoOrigen, filtroFincaOrigen.value);
    }
    if (filtroFincaDestino && filtroFincaDestino.value) {
        filterSelectRebanos(filtroRebanoDestino, filtroFincaDestino.value);
    }
});
</script>
@endsection
