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
        <div>
            <a href="{{ route('movimiento-rebano.create') }}"
               class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center font-medium">
                + Nuevo movimiento
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

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
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
            <div>
                <button type="button" onclick="limpiarFiltros(event)" class="w-full px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                    Limpiar filtros
                </button>
            </div>
        </div>
    </div>

    <!-- Summary KPIs -->
    @php
        $totalMovs = count($movimientos);
        $totalAnimalesTrasladados = array_sum(array_map(function($m) {
            return (int)($m['total_animales'] ?? (isset($m['animales']) && is_array($m['animales']) ? count($m['animales']) : ($m['animales_count'] ?? 0)));
        }, $movimientos));
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total movimientos</p>
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
                📋
            </div>
        </div>
    </div>

    <!-- Grid / Cards List -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        @if(count($movimientos) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="gridMovimientos">
                @foreach($movimientos as $movimiento)
                    @php
                        $idMov = $movimiento['id'] ?? $movimiento['id_Movimiento'] ?? 'N/A';
                        $fincaOrigId = $movimiento['finca_id'] ?? data_get($movimiento, 'finca_origen.id');
                        $rebanoOrigId = $movimiento['rebano_id'] ?? data_get($movimiento, 'rebano_origen.id');
                        $fincaDestId = $movimiento['finca_destino_id'] ?? $movimiento['finca_id_Destino'] ?? data_get($movimiento, 'finca_destino.id');
                        $rebanoDestId = $movimiento['rebano_destino_id'] ?? $movimiento['rebano_id_Destino'] ?? data_get($movimiento, 'rebano_destino_rel.id');

                        $fincaOrigNombre = data_get($movimiento, 'finca_origen.nombre') ?? ($mapaFincas[$fincaOrigId] ?? 'N/A');
                        $rebanoOrigNombre = data_get($movimiento, 'rebano_origen.nombre') ?? ($mapaRebanos[$rebanoOrigId] ?? 'Rebaño Origen');
                        $fincaDestNombre = data_get($movimiento, 'finca_destino.nombre') ?? ($mapaFincas[$fincaDestId] ?? 'N/A');
                        $rebanoDestNombre = data_get($movimiento, 'rebano_destino_rel.nombre') ?? ($mapaRebanos[$rebanoDestId] ?? ($movimiento['rebano_destino'] ?? 'Rebaño Destino'));

                        $cantAnimales = (int) (
                            $movimiento['total_animales']
                            ?? (isset($movimiento['animales']) && is_array($movimiento['animales']) ? count($movimiento['animales']) : ($movimiento['animales_count'] ?? 0))
                        );
                    @endphp
                    <div class="group border border-gray-200 hover:border-ganaderasoft-celeste rounded-2xl p-6 hover:shadow-lg transition-all duration-200 flex flex-col justify-between fila-movimiento"
                        data-finca-origen="{{ $fincaOrigId }}"
                        data-rebano-origen="{{ $rebanoOrigId }}"
                        data-finca-destino="{{ $fincaDestId }}"
                        data-rebano-destino="{{ $rebanoDestId }}"
                        data-cant-animales="{{ $cantAnimales }}">
                        <div>
                            <!-- Header with icon -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1 pr-2">
                                    <div class="flex items-center space-x-2 text-xs font-mono font-bold text-gray-400 mb-1">
                                        <span>#{{ $idMov }}</span>
                                        <span>•</span>
                                        <span class="font-sans font-medium text-gray-500">
                                            {{ isset($movimiento['created_at']) ? date('d/m/Y', strtotime($movimiento['created_at'])) : '--/--/----' }}
                                        </span>
                                    </div>
                                    <h3 class="text-base font-bold text-ganaderasoft-negro group-hover:text-ganaderasoft-azul transition-colors flex items-center gap-1.5 flex-wrap">
                                        <span>{{ $rebanoOrigNombre }}</span>
                                        <span class="text-gray-400">➔</span>
                                        <span class="text-ganaderasoft-azul">{{ $rebanoDestNombre }}</span>
                                    </h3>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-2xl group-hover:scale-105 transition-transform shrink-0">
                                    🔄
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="space-y-2 py-3 border-t border-b border-gray-100 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-500">Finca origen:</span>
                                    <span class="font-semibold text-gray-900 truncate max-w-[160px]">🏡 {{ $fincaOrigNombre }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-500">Finca destino:</span>
                                    <span class="font-semibold text-ganaderasoft-azul truncate max-w-[160px]">🏡 {{ $fincaDestNombre }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-500">Total animales:</span>
                                    <span class="px-2.5 py-0.5 rounded-full bg-green-50 text-green-700 font-bold badge-animales">
                                        {{ $cantAnimales }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2 mt-6 pt-2">
                            <a href="{{ route('movimiento-rebano.show', $idMov) }}"
                                class="flex-1 px-4 py-2.5 bg-ganaderasoft-celeste/15 hover:bg-ganaderasoft-celeste text-ganaderasoft-azul hover:text-white rounded-xl text-xs font-bold text-center transition-all duration-200">
                                Ver detalle
                            </a>
                            <a href="{{ route('movimiento-rebano.edit', $idMov) }}"
                                class="px-3 py-2.5 border border-gray-200 hover:border-gray-300 text-gray-700 rounded-xl text-xs font-semibold hover:bg-gray-50 transition-colors"
                                title="Editar movimiento">
                                ✏️
                            </a>
                            <form method="POST" action="{{ route('movimiento-rebano.destroy', $idMov) }}" class="inline"
                                onsubmit="return confirm('¿Está seguro de que desea eliminar este registro?')">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                    class="px-3 py-2.5 border border-red-100 hover:border-red-200 text-red-600 rounded-xl text-xs font-semibold hover:bg-red-50 transition-colors"
                                    title="Eliminar movimiento">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-ganaderasoft-celeste/10 flex items-center justify-center text-4xl">
                    🔄
                </div>
                <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay movimientos registrados</h3>
                <p class="text-gray-500 text-sm mb-6">Comienza registrando el primer movimiento de rebaño</p>
                <a href="{{ route('movimiento-rebano.create') }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                    + Nuevo movimiento
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filtroFincaOrigen  = document.getElementById('filtroFincaOrigen');
    const filtroRebanoOrigen = document.getElementById('filtroRebanoOrigen');
    const filtroFincaDestino = document.getElementById('filtroFincaDestino');
    const filtroRebanoDestino= document.getElementById('filtroRebanoDestino');

    function filterSelectRebanos(selectRebano, fincaId) {
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
        const fOrigen  = filtroFincaOrigen.value;
        const rOrigen  = filtroRebanoOrigen.value;
        const fDestino = filtroFincaDestino.value;
        const rDestino = filtroRebanoDestino.value;

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
        if (statTotal) statTotal.textContent = total;
        if (statAnimales) statAnimales.textContent = totalAnimales;
    }

    if (filtroFincaOrigen) {
        filtroFincaOrigen.addEventListener('change', function () {
            filterSelectRebanos(filtroRebanoOrigen, this.value);
            aplicarFiltros();
        });
    }

    if (filtroFincaDestino) {
        filtroFincaDestino.addEventListener('change', function () {
            filterSelectRebanos(filtroRebanoDestino, this.value);
            aplicarFiltros();
        });
    }

    if (filtroRebanoOrigen) filtroRebanoOrigen.addEventListener('change', aplicarFiltros);
    if (filtroRebanoDestino) filtroRebanoDestino.addEventListener('change', aplicarFiltros);

    window.limpiarFiltros = function (e) {
        if (e && e.preventDefault) e.preventDefault();
        if (filtroFincaOrigen) filtroFincaOrigen.value  = '';
        if (filtroRebanoOrigen) filtroRebanoOrigen.value = '';
        if (filtroFincaDestino) filtroFincaDestino.value = '';
        if (filtroRebanoDestino) filtroRebanoDestino.value= '';

        filterSelectRebanos(filtroRebanoOrigen, '');
        filterSelectRebanos(filtroRebanoDestino, '');

        if (window.history && window.history.pushState) {
            window.history.pushState({}, '', '{{ route('movimiento-rebano.index') }}');
        }
        aplicarFiltros();
    };
});
</script>
@endsection

