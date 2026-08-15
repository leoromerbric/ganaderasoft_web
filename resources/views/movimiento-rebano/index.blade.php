@extends('layouts.authenticated')

@section('title', 'Movimiento de Rebaño')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                🔄 Movimientos de Rebaño
            </h1>
            <p class="text-gray-500 text-sm mt-1">Gestión y registro de traslados de animales entre fincas y rebaños</p>
        </div>
        <div>
            <a href="{{ route('movimiento-rebano.create') }}"
               class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center text-sm gap-1.5">
                <span class="text-base font-bold">+</span> Nuevo Movimiento
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

    <!-- Filtros Bar en Vivo -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Finca Origen</label>
                <select id="filtroFincaOrigen" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todas las fincas</option>
                    @foreach($fincas as $finca)
                        @php $fId = $finca['id'] ?? $finca['finca_id'] ?? ''; @endphp
                        <option value="{{ $fId }}" {{ $fincaId == $fId ? 'selected' : '' }}>
                            {{ $finca['nombre'] ?? 'Finca #'.$fId }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Rebaño Origen</label>
                <select id="filtroRebanoOrigen" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todos los rebaños</option>
                    @foreach($rebanos as $rebano)
                        @php
                            $rId = $rebano['id'] ?? $rebano['rebano_id'] ?? '';
                            $fId = data_get($rebano, 'finca.id', $rebano['finca_id'] ?? '');
                        @endphp
                        <option value="{{ $rId }}" {{ $rebanoId == $rId ? 'selected' : '' }} data-finca="{{ $fId }}">
                            {{ $rebano['nombre'] ?? 'Rebaño #'.$rId }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Finca Destino</label>
                <select id="filtroFincaDestino" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todas las fincas</option>
                    @foreach($fincas as $finca)
                        @php $fId = $finca['id'] ?? $finca['finca_id'] ?? ''; @endphp
                        <option value="{{ $fId }}" {{ $fincaDestinoId == $fId ? 'selected' : '' }}>
                            {{ $finca['nombre'] ?? 'Finca #'.$fId }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Rebaño Destino</label>
                <select id="filtroRebanoDestino" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todos los rebaños</option>
                    @foreach($rebanos as $rebano)
                        @php
                            $rId = $rebano['id'] ?? $rebano['rebano_id'] ?? '';
                            $fId = data_get($rebano, 'finca.id', $rebano['finca_id'] ?? '');
                        @endphp
                        <option value="{{ $rId }}" {{ $rebanoDestinoId == $rId ? 'selected' : '' }} data-finca="{{ $fId }}">
                            {{ $rebano['nombre'] ?? 'Rebaño #'.$rId }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <a href="{{ route('movimiento-rebano.index') }}" onclick="limpiarFiltros(event)" class="w-full px-4 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                    Limpiar Filtros
                </a>
            </div>
        </div>
    </div>

    <!-- Tabla de Movimientos -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        @if(count($movimientos) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID Mov.</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Origen</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Destino</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Animales Movidos</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm" id="tablaMovimientos">
                        @foreach($movimientos as $movimiento)
                        @php
                            $idMov = $movimiento['id'] ?? $movimiento['id_Movimiento'] ?? 'N/A';
                            $fincaOrigId = $movimiento['finca_id'] ?? data_get($movimiento, 'finca_origen.id');
                            $rebanoOrigId = $movimiento['rebano_id'] ?? data_get($movimiento, 'rebano_origen.id');
                            $fincaDestId = $movimiento['finca_destino_id'] ?? $movimiento['finca_id_Destino'] ?? data_get($movimiento, 'finca_destino.id');
                            $rebanoDestId = $movimiento['rebano_destino_id'] ?? $movimiento['rebano_id_Destino'] ?? data_get($movimiento, 'rebano_destino_rel.id');

                            $fincaOrigNombre = data_get($movimiento, 'finca_origen.nombre') ?? ($mapaFincas[$fincaOrigId] ?? '-');
                            $rebanoOrigNombre = data_get($movimiento, 'rebano_origen.nombre') ?? ($mapaRebanos[$rebanoOrigId] ?? '-');
                            $fincaDestNombre = data_get($movimiento, 'finca_destino.nombre') ?? ($mapaFincas[$fincaDestId] ?? '-');
                            $rebanoDestNombre = data_get($movimiento, 'rebano_destino_rel.nombre') ?? ($mapaRebanos[$rebanoDestId] ?? ($movimiento['rebano_destino'] ?? '-'));

                            $cantAnimales = (int) (
                                $movimiento['total_animales']
                                ?? (isset($movimiento['animales']) && is_array($movimiento['animales']) ? count($movimiento['animales']) : ($movimiento['animales_count'] ?? 0))
                            );
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition-colors fila-movimiento"
                            data-finca-origen="{{ $fincaOrigId }}"
                            data-rebano-origen="{{ $rebanoOrigId }}"
                            data-finca-destino="{{ $fincaDestId }}"
                            data-rebano-destino="{{ $rebanoDestId }}">
                            <td class="px-6 py-4 whitespace-nowrap font-mono font-bold text-gray-900">
                                #{{ $idMov }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <p class="font-bold text-gray-900">{{ $rebanoOrigNombre }}</p>
                                    <p class="text-xs text-gray-500">🏡 {{ $fincaOrigNombre }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <p class="font-bold text-ganaderasoft-azul">{{ $rebanoDestNombre }}</p>
                                    <p class="text-xs text-gray-500">🏡 {{ $fincaDestNombre }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                    🐄 {{ $cantAnimales }} {{ $cantAnimales === 1 ? 'animal' : 'animales' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600 font-medium">
                                {{ isset($movimiento['created_at']) ? date('d/m/Y', strtotime($movimiento['created_at'])) : '--/--/----' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-3">
                                    <a href="{{ route('movimiento-rebano.show', $idMov) }}" 
                                       class="text-ganaderasoft-celeste hover:text-ganaderasoft-azul font-semibold transition-colors inline-flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Ver
                                    </a>
                                    <a href="{{ route('movimiento-rebano.edit', $idMov) }}" 
                                       class="text-amber-600 hover:text-amber-700 font-semibold transition-colors inline-flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('movimiento-rebano.destroy', $idMov) }}" class="inline"
                                          onsubmit="return confirm('¿Está seguro de que desea eliminar este registro?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-semibold transition-colors inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Eliminar
                                        </button>
                                    </form>
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
                    🔄
                </div>
                <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay movimientos registrados</h3>
                <p class="text-gray-500 text-sm mb-6">Comienza registrando el primer movimiento de rebaño</p>
                <a href="{{ route('movimiento-rebano.create') }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg text-sm">
                    + Nuevo Movimiento
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

        document.querySelectorAll('.fila-movimiento').forEach(function (row) {
            const matchFOrigen  = !fOrigen  || row.dataset.fincaOrigen  === fOrigen;
            const matchROrigen  = !rOrigen  || row.dataset.rebanoOrigen  === rOrigen;
            const matchFDestino = !fDestino || row.dataset.fincaDestino === fDestino;
            const matchRDestino = !rDestino || row.dataset.rebanoDestino === rDestino;

            const visible = matchFOrigen && matchROrigen && matchFDestino && matchRDestino;
            row.style.display = visible ? '' : 'none';
        });
    }

    filtroFincaOrigen.addEventListener('change', function () {
        filterSelectRebanos(filtroRebanoOrigen, this.value);
        aplicarFiltros();
    });

    filtroFincaDestino.addEventListener('change', function () {
        filterSelectRebanos(filtroRebanoDestino, this.value);
        aplicarFiltros();
    });

    filtroRebanoOrigen.addEventListener('change', aplicarFiltros);
    filtroRebanoDestino.addEventListener('change', aplicarFiltros);

    window.limpiarFiltros = function (e) {
        if (e && e.preventDefault) e.preventDefault();
        filtroFincaOrigen.value  = '';
        filtroRebanoOrigen.value = '';
        filtroFincaDestino.value = '';
        filtroRebanoDestino.value= '';

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
